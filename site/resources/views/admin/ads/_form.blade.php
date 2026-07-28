<div class="space-y-5">
    <div>
        <label for="title" class="label">Title <span class="accent-text">*</span></label>
        <input type="text" name="title" id="title" value="{{ old('title', $ad->title) }}" required class="input">
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="image" class="label">Image upload <span class="muted">(max 2 MB, 16:9 recommended)</span></label>
            <input type="file" name="image" id="image" accept="image/*"
                   class="input py-2 file:mr-3 file:rounded file:border-0 file:bg-zinc-200 file:px-3 file:py-1 file:text-xs file:text-zinc-800 dark:file:bg-ink-700 dark:file:text-ink-100">
            @if ($ad->image_path)
                <p class="mt-1.5 text-xs muted">Current: uploaded image. Upload a new one to replace it.</p>
            @endif
        </div>
        <div>
            <label for="image_url" class="label">or external image URL</label>
            <input type="url" name="image_url" id="image_url" value="{{ old('image_url', $ad->image_url) }}"
                   placeholder="https://example.com/banner.png" class="input">
            <p class="mt-1.5 text-xs muted">At least one of upload / URL is required.</p>
        </div>
    </div>

    <div>
        <label for="link_url" class="label">Link URL <span class="accent-text">*</span></label>
        <input type="url" name="link_url" id="link_url" value="{{ old('link_url', $ad->link_url) }}" required
               placeholder="https://example.com" class="input">
    </div>

    <div class="flex items-center gap-2 text-sm muted-strong">
        <input type="hidden" name="is_active" value="0">
        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $ad->is_active ?? true))
                   class="rounded border-zinc-300 text-accent-600 focus:ring-accent-500 dark:border-ink-600 dark:bg-ink-900">
            Active
        </label>
        <span class="muted">&middot; new ads are added at the end of the list; reorder by dragging on the ads list</span>
    </div>

    <div class="flex gap-3 pt-2">
        <button type="submit" class="btn-primary px-5 py-2.5">
            {{ $submitLabel ?? 'Save' }}
        </button>
        <a href="{{ route('admin.ads.index') }}" class="btn-outline px-5 py-2.5">Cancel</a>
    </div>
</div>
