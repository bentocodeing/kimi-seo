#!/usr/bin/env bash
set -euo pipefail

# Kimi SEO Installer
# Wraps everything in main() to prevent partial execution on network failure

main() {
    # Kimi Code is the primary install target. Pass --claude to install into
    # the Claude Code layout instead (upstream parity).
    TARGET="kimi"
    for arg in "$@"; do
        case "${arg}" in
            --claude) TARGET="claude" ;;
            *) echo "Unknown option: ${arg} (supported: --claude)"; exit 1 ;;
        esac
    done

    if [ "${TARGET}" = "claude" ]; then
        SKILLS_HOME="${HOME}/.claude/skills"
        AGENT_DIR="${HOME}/.claude/agents"
    else
        SKILLS_HOME="${HOME}/.kimi-code/skills"
        AGENT_DIR="${HOME}/.agents/agents"
    fi
    SKILL_DIR="${SKILLS_HOME}/seo"
    REPO_URL="https://github.com/bentocodeing/kimi-seo"
    # Pin to a specific release tag to prevent silent updates from main.
    # This default MUST be bumped on every release. CI guard
    # (tests/test_manifest_consistency.py) enforces this matches kimi.plugin.json.
    # Override: KIMI_SEO_TAG=main bash install.sh
    REPO_TAG="${KIMI_SEO_TAG:-v1.0.0}"

    echo "════════════════════════════════════════"
    echo "║   Kimi SEO - Installer             ║"
    echo "║   Kimi Code SEO Skill              ║"
    echo "════════════════════════════════════════"
    echo ""

    # Check prerequisites. The runtime launcher performs cross-platform Python
    # resolution and validates the minimum supported version.
    command -v git >/dev/null 2>&1 || { echo "✗ Git is required but not installed."; exit 1; }

    # Create directories
    mkdir -p "${SKILL_DIR}"
    mkdir -p "${AGENT_DIR}"

    # Clone or update
    TEMP_DIR=$(mktemp -d)
    cleanup() { rm -rf -- "${TEMP_DIR}"; }
    trap cleanup EXIT

    echo "↓ Downloading Kimi SEO (${REPO_TAG})..."
    git clone --depth 1 --branch "${REPO_TAG}" "${REPO_URL}" "${TEMP_DIR}/kimi-seo" 2>/dev/null

    # Copy skill files
    echo "→ Installing skill files..."
    cp -r "${TEMP_DIR}/kimi-seo/skills/seo/"* "${SKILL_DIR}/"

    # Copy sub-skills
    if [ -d "${TEMP_DIR}/kimi-seo/skills" ]; then
        for skill_dir in "${TEMP_DIR}/kimi-seo/skills"/*/; do
            skill_name=$(basename "${skill_dir}")
            target="${SKILLS_HOME}/${skill_name}"
            mkdir -p "${target}"
            cp -r "${skill_dir}"* "${target}/"
        done
    fi

    # Copy schema templates
    if [ -d "${TEMP_DIR}/kimi-seo/schema" ]; then
        mkdir -p "${SKILL_DIR}/schema"
        cp -r "${TEMP_DIR}/kimi-seo/schema/"* "${SKILL_DIR}/schema/"
    fi

    # Copy reference docs
    if [ -d "${TEMP_DIR}/kimi-seo/pdf" ]; then
        mkdir -p "${SKILL_DIR}/pdf"
        cp -r "${TEMP_DIR}/kimi-seo/pdf/"* "${SKILL_DIR}/pdf/"
    fi

    # Copy agents
    echo "→ Installing subagents..."
    cp -r "${TEMP_DIR}/kimi-seo/agents/"*.md "${AGENT_DIR}/" 2>/dev/null || true

    # Copy shared scripts
    if [ -d "${TEMP_DIR}/kimi-seo/scripts" ]; then
        mkdir -p "${SKILL_DIR}/scripts"
        cp -r "${TEMP_DIR}/kimi-seo/scripts/"* "${SKILL_DIR}/scripts/"
    fi

    # Copy the stable runtime launcher. Manual installs use its explicit path;
    # plugin installs expose the repository bin/ directory automatically.
    if [ -f "${TEMP_DIR}/kimi-seo/bin/kimi-seo" ]; then
        mkdir -p "${SKILL_DIR}/bin"
        cp "${TEMP_DIR}/kimi-seo/bin/kimi-seo" "${SKILL_DIR}/bin/kimi-seo"
        chmod +x "${SKILL_DIR}/bin/kimi-seo"
    fi

    # Copy hooks
    if [ -d "${TEMP_DIR}/kimi-seo/hooks" ]; then
        mkdir -p "${SKILL_DIR}/hooks"
        cp -r "${TEMP_DIR}/kimi-seo/hooks/"* "${SKILL_DIR}/hooks/"
        chmod +x "${SKILL_DIR}/hooks/"*.sh 2>/dev/null || true
        chmod +x "${SKILL_DIR}/hooks/"*.py 2>/dev/null || true
        # Manual installs copy hook files only; enforcement loads through the plugin manifest.
        if [ "${TARGET}" = "claude" ]; then
            echo "  Note: hook enforcement requires plugin install (/plugin install); manual hook copy is best-effort."
        else
            echo "  Note: hook enforcement requires plugin install (/plugins install ${REPO_URL}); manual hook copy is best-effort."
        fi
    fi

    # Copy extensions (optional add-ons: dataforseo, banana)
    if [ -d "${TEMP_DIR}/kimi-seo/extensions" ]; then
        echo "=> Installing extensions..."
        for ext_dir in "${TEMP_DIR}/kimi-seo/extensions"/*/; do
            [ -d "${ext_dir}" ] || continue
            ext_name=$(basename "${ext_dir}")
            # Extension skills
            if [ -d "${ext_dir}skills" ]; then
                for ext_skill in "${ext_dir}skills"/*/; do
                    [ -d "${ext_skill}" ] || continue
                    ext_skill_name=$(basename "${ext_skill}")
                    target="${SKILLS_HOME}/${ext_skill_name}"
                    mkdir -p "${target}"
                    cp -r "${ext_skill}"* "${target}/"
                done
            fi
            # Extension agents
            if [ -d "${ext_dir}agents" ]; then
                cp -r "${ext_dir}agents/"*.md "${AGENT_DIR}/" 2>/dev/null || true
            fi
            # Extension references
            if [ -d "${ext_dir}references" ]; then
                mkdir -p "${SKILL_DIR}/extensions/${ext_name}/references"
                cp -r "${ext_dir}references/"* "${SKILL_DIR}/extensions/${ext_name}/references/"
            fi
            # Extension scripts
            if [ -d "${ext_dir}scripts" ]; then
                mkdir -p "${SKILL_DIR}/extensions/${ext_name}/scripts"
                cp -r "${ext_dir}scripts/"* "${SKILL_DIR}/extensions/${ext_name}/scripts/"
            fi
        done
    fi

    # Copy requirements.txt to skill dir so users can retry later
    cp "${TEMP_DIR}/kimi-seo/requirements.txt" "${SKILL_DIR}/requirements.txt" 2>/dev/null || true
    cp "${TEMP_DIR}/kimi-seo/.claude-plugin/plugin.json" "${SKILL_DIR}/runtime-plugin.json" 2>/dev/null || true
    cp "${TEMP_DIR}/kimi-seo/kimi.plugin.json" "${SKILL_DIR}/kimi.plugin.json" 2>/dev/null || true

    # Manual installs cannot rely on plugin bin/ PATH injection. Rewrite only
    # exact files copied from this checkout during this install.
    if [ "${TARGET}" = "claude" ]; then
        MANUAL_BIN='$HOME/.claude/skills/seo/bin/kimi-seo'
    else
        MANUAL_BIN='$HOME/.kimi-code/skills/seo/bin/kimi-seo'
    fi
    rewrite_doc() {
        local doc="$1" temp_doc
        temp_doc="${doc}.kimi-seo-tmp"
        sed -e "s#kimi-seo run#\"${MANUAL_BIN}\" run#g" \
            -e "s#kimi-seo setup#\"${MANUAL_BIN}\" setup#g" \
            -e "s#kimi-seo doctor#\"${MANUAL_BIN}\" doctor#g" \
            "${doc}" > "${temp_doc}"
        mv "${temp_doc}" "${doc}"
    }
    for source_root in "${TEMP_DIR}/kimi-seo/skills"/*; do
        [ -d "${source_root}" ] || continue
        skill_name=$(basename "${source_root}")
        while IFS= read -r -d '' source_doc; do
            relative_doc=${source_doc#"${source_root}/"}
            doc="${SKILLS_HOME}/${skill_name}/${relative_doc}"
            [ -f "${doc}" ] && rewrite_doc "${doc}"
        done < <(find "${source_root}" -type f -name '*.md' -print0)
    done
    for source_root in "${TEMP_DIR}/kimi-seo/extensions"/*/skills/*; do
        [ -d "${source_root}" ] || continue
        skill_name=$(basename "${source_root}")
        while IFS= read -r -d '' source_doc; do
            relative_doc=${source_doc#"${source_root}/"}
            doc="${SKILLS_HOME}/${skill_name}/${relative_doc}"
            [ -f "${doc}" ] && rewrite_doc "${doc}"
        done < <(find "${source_root}" -type f -name '*.md' -print0)
    done
    for source_root in "${TEMP_DIR}/kimi-seo/extensions"/*/references; do
        [ -d "${source_root}" ] || continue
        ext_name=$(basename "$(dirname "${source_root}")")
        while IFS= read -r -d '' source_doc; do
            relative_doc=${source_doc#"${source_root}/"}
            doc="${SKILL_DIR}/extensions/${ext_name}/references/${relative_doc}"
            [ -f "${doc}" ] && rewrite_doc "${doc}"
        done < <(find "${source_root}" -type f -name '*.md' -print0)
    done
    for source_doc in "${TEMP_DIR}/kimi-seo/agents"/*.md "${TEMP_DIR}/kimi-seo/extensions"/*/agents/*.md; do
        [ -f "${source_doc}" ] || continue
        doc="${AGENT_DIR}/$(basename "${source_doc}")"
        [ -f "${doc}" ] && rewrite_doc "${doc}"
    done

    echo "→ Creating isolated Python runtime..."
    set +e
    "${SKILL_DIR}/bin/kimi-seo" setup
    runtime_status=$?
    set -e
    if [ "${runtime_status}" -ne 0 ] && [ "${runtime_status}" -ne 10 ]; then
        echo "✗ Core Python runtime setup failed. Installation is incomplete." >&2
        exit 1
    elif [ "${runtime_status}" -eq 10 ]; then
        echo "⚠ Core runtime installed, but Chromium setup is incomplete." >&2
    fi

    echo ""
    echo "✓ Kimi SEO installed successfully (${TARGET} target)!"
    echo ""
    echo "Usage:"
    if [ "${TARGET}" = "claude" ]; then
        echo "  1. Start Claude Code:  claude"
        echo "  2. Run commands:       /seo audit https://example.com"
    else
        echo "  1. Start Kimi Code:    kimi"
        echo "  2. Reload skills:      /reload"
        echo "  3. Run commands:       /seo audit https://example.com"
        echo ""
        echo "Managed alternative (recommended): install as a Kimi Code plugin instead:"
        echo "  /plugins install ${REPO_URL}"
        echo "  /reload"
    fi
    echo ""
    echo "Python deps location: ${SKILL_DIR}/requirements.txt"
    echo "Inspect remote scripts before piping them to bash."
    echo "To uninstall: curl -fsSL ${REPO_URL}/raw/main/uninstall.sh | bash"
}

main "$@"
