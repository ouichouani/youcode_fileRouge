@php
    $types = ['Question', 'History', 'Encouragement'];
    $visibilities = ['public', 'private', 'friends'];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Edit Post</title>

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        <style>
            :root {
                color-scheme: light;
                --page: #f4efe7;
                --ink: #21160f;
                --muted: #675248;
                --panel: rgba(255, 252, 248, 0.9);
                --line: rgba(82, 56, 43, 0.16);
                --accent: #2f7a72;
                --accent-deep: #1f5751;
                --focus: rgba(47, 122, 114, 0.18);
                --danger: #b8341f;
            }

            * { box-sizing: border-box; }

            body {
                margin: 0;
                min-height: 100vh;
                font-family: "Instrument Sans", ui-sans-serif, system-ui, sans-serif;
                color: var(--ink);
                background:
                    radial-gradient(circle at top right, rgba(189, 224, 217, 0.7), transparent 36%),
                    radial-gradient(circle at bottom left, rgba(47, 122, 114, 0.14), transparent 24%),
                    linear-gradient(135deg, #fffdfa 0%, var(--page) 46%, #e8e1d8 100%);
            }

            .shell {
                width: min(1120px, calc(100% - 32px));
                margin: 40px auto;
                display: grid;
                gap: 24px;
                grid-template-columns: 0.92fr 1.08fr;
            }

            .panel {
                background: var(--panel);
                border: 1px solid var(--line);
                border-radius: 28px;
                box-shadow: 0 24px 60px rgba(50, 35, 27, 0.11);
                backdrop-filter: blur(16px);
            }

            .summary, .form-card { padding: 32px; }

            .eyebrow {
                display: inline-flex;
                align-items: center;
                padding: 8px 14px;
                border-radius: 999px;
                background: rgba(47, 122, 114, 0.1);
                color: var(--accent-deep);
                font-size: 13px;
                font-weight: 700;
                letter-spacing: 0.08em;
                text-transform: uppercase;
            }

            h1 {
                margin: 18px 0 12px;
                font-size: clamp(2.1rem, 4vw, 3.7rem);
                line-height: 0.97;
            }

            h2 {
                margin: 0 0 8px;
                font-size: 1.55rem;
            }

            .helper, .snapshot-copy, .field-note { color: var(--muted); }

            .snapshot {
                margin-top: 28px;
                padding: 20px;
                border-radius: 24px;
                border: 1px solid rgba(82, 56, 43, 0.1);
                background: linear-gradient(180deg, rgba(255, 255, 255, 0.82), rgba(242, 249, 247, 0.82));
            }

            .snapshot-label {
                display: inline-flex;
                margin-bottom: 12px;
                padding: 6px 10px;
                border-radius: 999px;
                background: rgba(47, 122, 114, 0.1);
                color: var(--accent-deep);
                font-size: 0.78rem;
                font-weight: 700;
                letter-spacing: 0.08em;
                text-transform: uppercase;
            }

            .snapshot-content {
                margin: 0 0 16px;
                font-size: 1.02rem;
                line-height: 1.8;
                white-space: pre-wrap;
            }

            .meta {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
            }

            .meta span {
                display: inline-flex;
                align-items: center;
                min-height: 36px;
                padding: 0 14px;
                border-radius: 999px;
                background: rgba(255, 255, 255, 0.78);
                border: 1px solid rgba(82, 56, 43, 0.12);
                font-size: 0.92rem;
            }

            .form-grid {
                display: grid;
                gap: 18px;
                margin-top: 28px;
            }

            .grid-two {
                display: grid;
                gap: 18px;
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .label {
                display: block;
                margin-bottom: 6px;
                font-size: 0.95rem;
                font-weight: 700;
            }

            .textarea, .select, .file-input {
                width: 100%;
                border: 1px solid rgba(82, 56, 43, 0.16);
                border-radius: 18px;
                background: rgba(255, 255, 255, 0.86);
                color: var(--ink);
                padding: 15px 16px;
                font: inherit;
                transition: border-color 0.2s ease, box-shadow 0.2s ease;
            }

            .textarea {
                min-height: 180px;
                resize: vertical;
            }

            .textarea:focus, .select:focus, .file-input:focus {
                outline: none;
                border-color: var(--accent);
                box-shadow: 0 0 0 5px var(--focus);
            }

            .field-error .textarea,
            .field-error .select,
            .field-error .file-input {
                border-color: rgba(184, 52, 31, 0.6);
                box-shadow: 0 0 0 5px rgba(184, 52, 31, 0.08);
            }

            .field-note, .error {
                margin-top: 8px;
                font-size: 0.92rem;
                line-height: 1.5;
            }

            .error { color: var(--danger); }

            .alert {
                margin-bottom: 22px;
                padding: 14px 16px;
                border-radius: 18px;
                background: rgba(184, 52, 31, 0.08);
                border: 1px solid rgba(184, 52, 31, 0.18);
                color: #7d2216;
            }

            .actions {
                display: flex;
                flex-wrap: wrap;
                gap: 14px;
                align-items: center;
                margin-top: 10px;
            }

            .button, .ghost {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-height: 52px;
                padding: 0 22px;
                border-radius: 999px;
                font-weight: 700;
                text-decoration: none;
                cursor: pointer;
            }

            .button {
                border: 0;
                background: linear-gradient(135deg, var(--accent) 0%, var(--accent-deep) 100%);
                color: #f7fffd;
                box-shadow: 0 14px 28px rgba(31, 87, 81, 0.22);
            }

            .ghost {
                border: 1px solid rgba(82, 56, 43, 0.16);
                color: var(--ink);
                background: rgba(255, 255, 255, 0.68);
            }

            @media (max-width: 920px) {
                .shell {
                    grid-template-columns: 1fr;
                    margin: 24px auto;
                }
            }

            @media (max-width: 640px) {
                .summary, .form-card { padding: 22px; }
                .grid-two { grid-template-columns: 1fr; }
            }
        </style>
    </head>
    <body>
        <main class="shell">
            <section class="panel summary">
                <span class="eyebrow">Refine post</span>
                <h1>Edit the post without losing its voice.</h1>
                <p class="helper">
                    Adjust the wording, switch the category, or change who can see it. The form starts with the
                    current post values and keeps your submitted input if validation fails.
                </p>

                <div class="snapshot">
                    <span class="snapshot-label">Current post snapshot</span>
                    <p class="snapshot-content">{{ $post->content ?: 'This post currently relies on images or a very short message.' }}</p>
                    <div class="meta">
                        <span>Type: {{ $post->type ?? 'Not set' }}</span>
                        <span>Visibility: {{ ucfirst($post->visibility ?? 'not set') }}</span>
                        <span>Post #{{ $post->id }}</span>
                    </div>
                    <p class="snapshot-copy" style="margin-top: 16px;">
                        Updating images is supported by the request rules too, so you can attach new ones if your controller handles storage.
                    </p>
                </div>
            </section>

            <section class="panel form-card">
                <h2>Update post</h2>
                <p class="helper">Make the changes you want, then save the updated version.</p>

                @if ($errors->any())
                    <div class="alert">
                        A few fields still need attention before this update can be saved.
                    </div>
                @endif

                <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data" class="form-grid">
                    @csrf
                    @method('PUT')

                    <div class="@error('content') field-error @enderror">
                        <label class="label" for="content">Post content</label>
                        <textarea class="textarea" name="content" id="content" placeholder="Refresh the message, sharpen the story, or ask a better question.">{{ old('content', $post->content) }}</textarea>
                        <p class="field-note">Leave this empty only if your update relies on images.</p>
                        @error('content')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid-two">
                        <div class="@error('type') field-error @enderror">
                            <label class="label" for="type">Post type</label>
                            <select class="select" name="type" id="type">
                                <option value="" {{ old('type', $post->type) ? '' : 'selected' }}>Keep current type</option>
                                @foreach ($types as $type)
                                    <option value="{{ $type }}" {{ old('type', $post->type) === $type ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                            @error('type')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="@error('visibility') field-error @enderror">
                            <label class="label" for="visibility">Visibility</label>
                            <select class="select" name="visibility" id="visibility">
                                <option value="" {{ old('visibility', $post->visibility) ? '' : 'selected' }}>Keep current visibility</option>
                                @foreach ($visibilities as $visibility)
                                    <option value="{{ $visibility }}" {{ old('visibility', $post->visibility) === $visibility ? 'selected' : '' }}>
                                        {{ ucfirst($visibility) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('visibility')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="@error('images') field-error @enderror @error('images.*') field-error @enderror">
                        <label class="label" for="images">Replace or add images</label>
                        <input class="file-input" type="file" name="images[]" id="images" accept="image/*" multiple>
                        <p class="field-note">Optional. Upload images only if you want this update to include new media.</p>
                        @error('images')
                            <p class="error">{{ $message }}</p>
                        @enderror
                        @error('images.*')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="actions">
                        <button class="button" type="submit">Save changes</button>
                        <a class="ghost" href="{{ route('posts.index') }}">Back to posts</a>
                    </div>
                </form>
            </section>
        </main>
    </body>
</html>
