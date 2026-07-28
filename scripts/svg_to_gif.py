#!/usr/bin/env python3
"""Render an animated SVG to a GIF by stepping CSS animations deterministically.

Usage:
    svg_to_gif.py <input.svg> <output.gif> [--loop-seconds N] [--fps N]

Loads the SVG in headless Chromium, pauses every CSS animation via the Web
Animations API, steps currentTime frame by frame, screenshots each step, and
assembles the frames into an optimized GIF with Pillow.
"""

import argparse
import io
import sys
from pathlib import Path

from PIL import Image
from playwright.sync_api import sync_playwright


def render(svg_path: Path, out_path: Path, loop_seconds: float, fps: int) -> None:
    svg = svg_path.resolve()
    html = (
        "<!doctype html><html><head><style>"
        "html,body{margin:0;padding:0;background:transparent}"
        "</style></head><body>"
        + svg.read_text(encoding="utf-8")
        + "</body></html>"
    )

    frame_ms = 1000 / fps
    n_frames = round(loop_seconds * fps)

    with sync_playwright() as p:
        browser = p.chromium.launch()
        page = browser.new_page(viewport={"width": 880, "height": 500})
        page.set_content(html, wait_until="load")
        # Pause all CSS animations so we can seek them deterministically.
        page.evaluate("() => document.getAnimations().forEach(a => a.pause())")

        frames = []
        for i in range(n_frames):
            t = i * frame_ms
            page.evaluate(
                "(t) => document.getAnimations().forEach(a => { a.currentTime = t; })",
                t,
            )
            png = page.screenshot(type="png")
            frames.append(Image.open(io.BytesIO(png)).convert("P", palette=Image.ADAPTIVE, colors=128))
        browser.close()

    out_path.parent.mkdir(parents=True, exist_ok=True)
    frames[0].save(
        out_path,
        save_all=True,
        append_images=frames[1:],
        duration=round(frame_ms),
        loop=0,
        optimize=True,
        disposal=2,
    )
    size_kb = out_path.stat().st_size // 1024
    print(f"wrote {out_path} ({n_frames} frames, {size_kb} KB)")


def main() -> int:
    ap = argparse.ArgumentParser()
    ap.add_argument("svg", type=Path)
    ap.add_argument("gif", type=Path)
    ap.add_argument("--loop-seconds", type=float, required=True)
    ap.add_argument("--fps", type=float, default=10)
    args = ap.parse_args()
    render(args.svg, args.gif, args.loop_seconds, args.fps)
    return 0


if __name__ == "__main__":
    sys.exit(main())
