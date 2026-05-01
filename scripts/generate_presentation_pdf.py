from __future__ import annotations

from pathlib import Path

PAGE_WIDTH = 842
PAGE_HEIGHT = 595
MARGIN_X = 56
TOP_Y = 520
TITLE_SIZE = 24
SUBTITLE_SIZE = 15
BODY_SIZE = 13
LINE_GAP = 22
BULLET_GAP = 20


def escape_pdf_text(text: str) -> str:
    return text.replace("\\", "\\\\").replace("(", "\\(").replace(")", "\\)")


def wrap_text(text: str, max_chars: int) -> list[str]:
    words = text.split()
    if not words:
        return [""]

    lines: list[str] = []
    current = words[0]

    for word in words[1:]:
        candidate = f"{current} {word}"
        if len(candidate) <= max_chars:
            current = candidate
        else:
            lines.append(current)
            current = word

    lines.append(current)
    return lines


def parse_slides(markdown: str) -> list[dict[str, object]]:
    slides: list[dict[str, object]] = []
    current: dict[str, object] | None = None

    for raw_line in markdown.splitlines():
        line = raw_line.strip()
        if not line:
            continue

        if line.startswith("## Slide:"):
            if current:
                slides.append(current)
            current = {"name": line.replace("## Slide:", "", 1).strip(), "title": "", "bullets": []}
            continue

        if current is None:
            continue

        if line.startswith("### "):
            current["title"] = line[4:].strip()
            continue

        if line.startswith("- "):
            bullets = current.setdefault("bullets", [])
            assert isinstance(bullets, list)
            bullets.append(line[2:].strip())

    if current:
        slides.append(current)

    return slides


def text_stream(x: int, y: int, size: int, text: str) -> str:
    safe = escape_pdf_text(text)
    return f"BT /F1 {size} Tf 1 0 0 1 {x} {y} Tm ({safe}) Tj ET"


def build_page_content(slide: dict[str, object], page_number: int, total_pages: int) -> str:
    parts: list[str] = []
    y = TOP_Y

    title = str(slide.get("title", ""))
    parts.append(text_stream(MARGIN_X, y, TITLE_SIZE, title))
    y -= 42

    bullets = slide.get("bullets", [])
    assert isinstance(bullets, list)

    for bullet in bullets:
        wrapped = wrap_text(str(bullet), 78)
        first = True
        for line in wrapped:
            prefix = "• " if first else "  "
            parts.append(text_stream(MARGIN_X + 8, y, BODY_SIZE, prefix + line))
            y -= BULLET_GAP
            first = False
        y -= 8

    footer = f"FileRouge Presentation  |  Slide {page_number}/{total_pages}"
    parts.append(text_stream(MARGIN_X, 28, 10, footer))
    return "\n".join(parts)


def make_pdf(slides: list[dict[str, object]], output_path: Path) -> None:
    objects: list[bytes] = []

    def add_object(data: str | bytes) -> int:
        payload = data.encode("latin-1", errors="replace") if isinstance(data, str) else data
        objects.append(payload)
        return len(objects)

    font_obj = add_object("<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>")

    page_ids: list[int] = []
    content_ids: list[int] = []

    placeholder_pages_obj = add_object("<< /Type /Pages /Kids [] /Count 0 >>")
    catalog_obj = add_object(f"<< /Type /Catalog /Pages {placeholder_pages_obj} 0 R >>")

    total_pages = len(slides)
    for index, slide in enumerate(slides, start=1):
        content = build_page_content(slide, index, total_pages)
        content_bytes = content.encode("latin-1", errors="replace")
        content_obj = add_object(
            b"<< /Length "
            + str(len(content_bytes)).encode("ascii")
            + b" >>\nstream\n"
            + content_bytes
            + b"\nendstream"
        )
        content_ids.append(content_obj)

        page_obj = add_object(
            f"<< /Type /Page /Parent {placeholder_pages_obj} 0 R "
            f"/MediaBox [0 0 {PAGE_WIDTH} {PAGE_HEIGHT}] "
            f"/Resources << /Font << /F1 {font_obj} 0 R >> >> "
            f"/Contents {content_obj} 0 R >>"
        )
        page_ids.append(page_obj)

    kids = " ".join(f"{page_id} 0 R" for page_id in page_ids)
    objects[placeholder_pages_obj - 1] = f"<< /Type /Pages /Kids [{kids}] /Count {len(page_ids)} >>".encode("latin-1")

    pdf = bytearray(b"%PDF-1.4\n")
    offsets = [0]
    for i, obj in enumerate(objects, start=1):
        offsets.append(len(pdf))
        pdf.extend(f"{i} 0 obj\n".encode("ascii"))
        pdf.extend(obj)
        pdf.extend(b"\nendobj\n")

    xref_offset = len(pdf)
    pdf.extend(f"xref\n0 {len(objects) + 1}\n".encode("ascii"))
    pdf.extend(b"0000000000 65535 f \n")
    for offset in offsets[1:]:
        pdf.extend(f"{offset:010d} 00000 n \n".encode("ascii"))

    pdf.extend(
        (
            f"trailer\n<< /Size {len(objects) + 1} /Root {catalog_obj} 0 R >>\n"
            f"startxref\n{xref_offset}\n%%EOF\n"
        ).encode("ascii")
    )

    output_path.parent.mkdir(parents=True, exist_ok=True)
    output_path.write_bytes(pdf)


def main() -> None:
    base_dir = Path(__file__).resolve().parents[1]
    source = base_dir / "docs" / "project_presentation.md"
    output = base_dir / "docs" / "project_presentation.pdf"
    slides = parse_slides(source.read_text(encoding="utf-8"))
    make_pdf(slides, output)
    print(output)


if __name__ == "__main__":
    main()
