<div wire:key="{{ rand() }}">
    <livewire:editorjs
        editor-id="detailsEditor"
        :value="$value"
        upload-disk="public"
        download-disk="public"
        class="..."
        :read-only="false"
        placeholder="Description or [tab] for options"
    />
</div>