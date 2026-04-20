@extends('components.layout')

@section('title')
    CREATE POST
@endsection

@section('content')


@php
    $types = ['Question', 'History', 'Encouragement'];
    $visibilities = ['public', 'private', 'friends'];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Create Post</title>

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        <style>
            :root {
                color-scheme: light;
                --page: #f7efe5;
                --ink: #2c180f;
                --muted: #6d5245;
                --panel: rgba(255, 252, 247, 0.88);
                --line: rgba(95, 57, 34, 0.16);
                --accent: #c85b2b;
                --accent-deep: #8c2f16;
                --focus: rgba(200, 91, 43, 0.18);
                --danger: #b8341f;
            }

            * { box-sizing: border-box; }

            body {
                margin: 0;
                min-height: 100vh;
                font-family: "Instrument Sans", ui-sans-serif, system-ui, sans-serif;
                color: var(--ink);
                background:
                    radial-gradient(circle at top left, rgba(243, 201, 168, 0.7), transparent 34%),
                    radial-gradient(circle at bottom right, rgba(200, 91, 43, 0.18), transparent 28%),
                    linear-gradient(135deg, #fff8f1 0%, var(--page) 48%, #f4e3d6 100%);
            }

            .shell {
                width: min(1080px, calc(100% - 32px));
                margin: 40px auto;
                display: grid;
                gap: 24px;
                grid-template-columns: 1.1fr 0.9fr;
            }

            .panel {
                background: var(--panel);
                border: 1px solid var(--line);
                border-radius: 28px;
                box-shadow: 0 24px 60px rgba(88, 52, 31, 0.12);
                backdrop-filter: blur(16px);
            }

            .hero, .form-card { padding: 32px; }

            .eyebrow {
                display: inline-flex;
                align-items: center;
                gap: 10px;
                padding: 8px 14px;
                border-radius: 999px;
                background: rgba(200, 91, 43, 0.1);
                color: var(--accent-deep);
                font-size: 13px;
                font-weight: 700;
                letter-spacing: 0.08em;
                text-transform: uppercase;
            }

            h1 {
                margin: 20px 0 12px;
                font-size: clamp(2.2rem, 4vw, 4rem);
                line-height: 0.95;
            }

            .lead, .helper, .field-note { color: var(--muted); }

            .lead {
                font-size: 1.05rem;
                line-height: 1.7;
                max-width: 28rem;
            }

            .tips {
                margin-top: 28px;
                display: grid;
                gap: 14px;
            }

            .tip {
                padding: 16px 18px;
                border-radius: 18px;
                background: rgba(255, 255, 255, 0.64);
                border: 1px solid rgba(95, 57, 34, 0.1);
            }

            .tip strong, .label {
                display: block;
                margin-bottom: 6px;
            }

            .form-card h2 {
                margin: 0 0 8px;
                font-size: 1.55rem;
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
                font-size: 0.95rem;
                font-weight: 700;
            }

            .textarea, .select, .file-input {
                width: 100%;
                border: 1px solid rgba(95, 57, 34, 0.16);
                border-radius: 18px;
                background: rgba(255, 255, 255, 0.82);
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
                color: #fff9f4;
                box-shadow: 0 14px 28px rgba(140, 47, 22, 0.24);
            }

            .ghost {
                border: 1px solid rgba(95, 57, 34, 0.16);
                color: var(--ink);
                background: rgba(255, 255, 255, 0.65);
            }

            @media (max-width: 900px) {
                .shell {
                    grid-template-columns: 1fr;
                    margin: 24px auto;
                }
            }

            @media (max-width: 640px) {
                .hero, .form-card { padding: 22px; }
                .grid-two { grid-template-columns: 1fr; }
            }
        </style>
    </head>
    <body>
        <main class="shell">
            <section class="panel hero">
                <span class="eyebrow">New story</span>
                <h1>Shape a post that feels worth stopping for.</h1>
                <p class="lead">
                    Write something clear, choose how people should discover it, and attach images when the post
                    needs more atmosphere than text alone can give.
                </p>

                <div class="tips">
                    <article class="tip">
                        <strong>Choose the right tone</strong>
                        <p class="helper">Use <em>Question</em> for conversation, <em>History</em> for storytelling, and <em>Encouragement</em> when you want to lift someone up.</p>
                    </article>
                    <article class="tip">
                        <strong>Visibility matters</strong>
                        <p class="helper">Public posts travel furthest, friends stays personal, and private lets you draft thoughts more quietly.</p>
                    </article>
                    <article class="tip">
                        <strong>Text or images</strong>
                        <p class="helper">Your validation already allows either. One meaningful paragraph or a strong image set is enough to publish.</p>
                    </article>
                </div>
            </section>

            <section class="panel form-card">
                <h2>Create post</h2>
                <p class="helper">Fill in the fields below, then publish when everything looks right.</p>

                @if ($errors->any())
                    <div class="alert">
                        There are a few things to fix before this post can be published.
                    </div>
                @endif

                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="form-grid">
                    @csrf

                    <div class="@error('content') field-error @enderror">
                        <label class="label" for="content">Post content</label>
                        <textarea class="textarea" name="content" id="content" placeholder="What do you want people to read, remember, or respond to?">{{ old('content') }}</textarea>
                        <p class="field-note">Up to 500 characters. You can leave this empty if you upload images instead.</p>
                        @error('content')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid-two">
                        <div class="@error('type') field-error @enderror">
                            <label class="label" for="type">Post type</label>
                            <select class="select" name="type" id="type" required>
                                <option value="Question"> Question</option>
                                <option value="History"> History</option>
                                <option value="Encouragement"> Encouragement</option>
                            </select>
                            @error('type')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="@error('visibility') field-error @enderror">
                            <label class="label" for="visibility">Visibility</label>
                            <select class="select" name="visibility" id="visibility" required>
                                <option value="" disabled {{ old('visibility') ? '' : 'selected' }}>Choose visibility</option>
                                    <option value ="public">public</option>
                                    <option value ="private">private</option>
                                    <option value ="friends">friends</option>
                            </select>
                            @error('visibility')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </div>


                    </div>

                    <div class="@error('images') field-error @enderror @error('images.*') field-error @enderror">
                        <label class="label" for="images">Images</label>
                        <input class="file-input" type="file" name="images[]" id="images" accept="image/*" multiple>
                        <p class="field-note">PNG, JPG, JPEG, or GIF up to 2MB each. Add multiple files if the post needs a small gallery.</p>
                        @error('images')
                            <p class="error">{{ $message }}</p>
                        @enderror
                        @error('images.*')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="actions">
                        <button class="button" type="submit">Publish post</button>
                        <a class="ghost" href="{{ route('posts.index') }}">Back to posts</a>
                    </div>
                </form>
            </section>
        </main>
    </body>
</html>


@endsection