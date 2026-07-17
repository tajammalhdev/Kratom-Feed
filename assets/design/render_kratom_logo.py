"""
Kratom Feed — Botanical Signal logo renderer (refined pass).
Circular icon left, wordmark right.
"""

from __future__ import annotations

import math
from pathlib import Path

from PIL import Image, ImageDraw, ImageFont, ImageFilter

ROOT = Path(__file__).resolve().parent.parent
OUT_DIR = ROOT / "images"
FONT_DIR = Path(r"c:\Users\tajam\.cursor\skills\canvas-design\canvas-fonts")

PG_GREEN = (66, 178, 81)
PG_GREEN_DARK = (44, 119, 54)
PG_GREEN_DEEP = (24, 72, 34)
PG_LIME = (184, 240, 74)
PG_GREEN_LIGHT = (225, 244, 227)
PG_DARK = (11, 26, 19)
WHITE = (255, 255, 255)


def lerp(a: float, b: float, t: float) -> float:
    return a + (b - a) * t


def lerp_color(c1: tuple[int, int, int], c2: tuple[int, int, int], t: float) -> tuple[int, int, int]:
    return (int(lerp(c1[0], c2[0], t)), int(lerp(c1[1], c2[1], t)), int(lerp(c1[2], c2[2], t)))


def load_font(name: str, size: int) -> ImageFont.FreeTypeFont:
    path = FONT_DIR / name
    if path.exists():
        return ImageFont.truetype(str(path), size)
    fallback = Path(r"C:\Windows\Fonts") / ("georgiab.ttf" if "Cormorant" in name else "segoeuib.ttf")
    return ImageFont.truetype(str(fallback), size)


def cubic_bezier(p0, p1, p2, p3, steps: int = 24) -> list[tuple[float, float]]:
    pts: list[tuple[float, float]] = []
    for i in range(steps + 1):
        t = i / steps
        u = 1 - t
        x = u**3 * p0[0] + 3 * u**2 * t * p1[0] + 3 * u * t**2 * p2[0] + t**3 * p3[0]
        y = u**3 * p0[1] + 3 * u**2 * t * p1[1] + 3 * u * t**2 * p2[1] + t**3 * p3[1]
        pts.append((x, y))
    return pts


def kratom_leaf_outline(cx: float, cy: float, width: float, height: float, lean: float = 0.0) -> list[tuple[float, float]]:
    """Single blade — pointed apex, rounded base, subtle lean."""
    w, h = width * 0.5, height * 0.5
    base = (cx + lean * h * 0.15, cy + h)
    tip = (cx + lean * h * 0.35, cy - h * 0.92)
    left = (
        cubic_bezier(
            base,
            (cx - w * 1.05, cy + h * 0.45),
            (cx - w * 0.95, cy - h * 0.15),
            tip,
        )[:-1]
        + cubic_bezier(
            tip,
            (cx + w * 0.95, cy - h * 0.15),
            (cx + w * 1.05, cy + h * 0.45),
            base,
        )
    )
    return left


def draw_leaf(
    draw: ImageDraw.ImageDraw,
    cx: float,
    cy: float,
    width: float,
    height: float,
    lean: float,
    fill: tuple[int, int, int, int],
    midrib: bool = True,
) -> None:
    outline = kratom_leaf_outline(cx, cy, width, height, lean)
    draw.polygon(outline, fill=fill)

    if midrib:
        base = (cx + lean * height * 0.075, cy + height * 0.5)
        tip = (cx + lean * height * 0.175, cy - height * 0.46)
        vein = (*lerp_color(fill[:3], PG_DARK, 0.42), min(255, fill[3] + 25))
        draw.line([base, tip], fill=vein, width=max(1, int(width * 0.045)))


def radial_gradient(size: int, inner: tuple[int, int, int], outer: tuple[int, int, int]) -> Image.Image:
    img = Image.new("RGBA", (size, size))
    cx = cy = size / 2
    max_r = size / 2
    px = img.load()
    for y in range(size):
        for x in range(size):
            d = min(1.0, math.hypot(x - cx + 0.5, y - cy + 0.5) / max_r)
            t = d ** 1.28
            c = lerp_color(inner, outer, t)
            px[x, y] = (*c, 255)
    return img


def draw_icon(diameter: int, supersample: int = 4) -> Image.Image:
    s = diameter * supersample
    canvas = Image.new("RGBA", (s, s), (0, 0, 0, 0))
    cx = cy = s / 2
    r = s * 0.455

    # Ambient glow
    glow = Image.new("RGBA", (s, s), (0, 0, 0, 0))
    ImageDraw.Draw(glow).ellipse([cx - r * 1.04, cy - r * 1.04, cx + r * 1.04, cy + r * 1.04], fill=(*PG_GREEN_DARK, 14))
    canvas = Image.alpha_composite(canvas, glow.filter(ImageFilter.GaussianBlur(radius=s * 0.022)))

    # Circle body
    grad = radial_gradient(int(r * 2), (72, 188, 88), PG_GREEN_DARK)
    mask = Image.new("L", (s, s), 0)
    ImageDraw.Draw(mask).ellipse([cx - r, cy - r, cx + r, cy + r], fill=255)
    layer = Image.new("RGBA", (s, s), (0, 0, 0, 0))
    layer.paste(grad, (int(cx - r), int(cy - r)), grad)
    canvas = Image.alpha_composite(canvas, Image.composite(layer, Image.new("RGBA", (s, s), (0, 0, 0, 0)), mask))

    # Inner rim highlight
    rim = Image.new("RGBA", (s, s), (0, 0, 0, 0))
    rd = ImageDraw.Draw(rim)
    rd.ellipse([cx - r * 0.965, cy - r * 0.965, cx + r * 0.965, cy + r * 0.965], outline=(*PG_LIME, 44), width=max(1, int(s * 0.0055)))
    canvas = Image.alpha_composite(canvas, rim)

    draw = ImageDraw.Draw(canvas)

    # Layered crown — back to front (Mitragyna silhouette)
    specs = [
        (0.0, 0.78, 0.62, 0.0, (*PG_GREEN_DEEP, 175), False),
        (-0.14, 0.72, 0.56, -0.12, (*PG_GREEN_DARK, 215), True),
        (0.14, 0.72, 0.56, 0.12, (*PG_GREEN_DARK, 215), True),
        (0.0, 0.66, 0.52, 0.0, (*PG_GREEN_LIGHT, 245), True),
    ]
    for lean, lw, lh, offset_x, fill, midrib in specs:
        draw_leaf(
            draw,
            cx + offset_x * r,
            cy + r * 0.08,
            r * lw,
            r * lh,
            lean,
            fill,
            midrib=midrib,
        )

    # Petiole
    draw.rounded_rectangle(
        [cx - s * 0.008, cy + r * 0.28, cx + s * 0.008, cy + r * 0.42],
        radius=s * 0.004,
        fill=(*PG_DARK, 85),
    )

    # Canopy light — single accent
    dot = s * 0.016
    draw.ellipse(
        [cx - r * 0.52 - dot, cy - r * 0.68 - dot, cx - r * 0.52 + dot, cy - r * 0.68 + dot],
        fill=(*PG_LIME, 215),
    )

    return canvas.resize((diameter, diameter), Image.Resampling.LANCZOS)


def draw_wordmark(width: int, height: int, primary_size: int, secondary_size: int, dark: bool = False) -> Image.Image:
    img = Image.new("RGBA", (width, height), (0, 0, 0, 0))
    draw = ImageDraw.Draw(img)

    font_kratom = load_font("CormorantGaramond-SemiBold.ttf", primary_size)
    font_feed = load_font("PlusJakartaSans-Bold.ttf", secondary_size)

    kratom, feed = "Kratom", "Feed"
    gap = int(primary_size * 0.16)

    k_bbox = draw.textbbox((0, 0), kratom, font=font_kratom)
    f_bbox = draw.textbbox((0, 0), feed, font=font_feed)
    k_w, k_h = k_bbox[2] - k_bbox[0], k_bbox[3] - k_bbox[1]
    f_w, f_h = f_bbox[2] - f_bbox[0], f_bbox[3] - f_bbox[1]

    total_w = k_w + gap + f_w
    x0 = (width - total_w) // 2
    y0 = (height - max(k_h, f_h)) // 2

    k_color = WHITE if dark else PG_DARK
    f_color = PG_LIME if dark else PG_GREEN

    draw.text((x0 - k_bbox[0], y0 - k_bbox[1]), kratom, font=font_kratom, fill=k_color)
    feed_x = x0 + k_w + gap
    feed_y = y0 + int((k_h - f_h) * 0.52)
    draw.text((feed_x - f_bbox[0], feed_y - f_bbox[1]), feed, font=font_feed, fill=f_color)

    return img


def compose_logo(
    icon_d: int = 200,
    padding: int = 48,
    gap: int = 36,
    primary_size: int = 88,
    secondary_size: int = 52,
    transparent: bool = True,
    dark: bool = False,
) -> Image.Image:
    icon = draw_icon(icon_d)

    dummy = Image.new("RGBA", (1, 1))
    d = ImageDraw.Draw(dummy)
    fk = load_font("CormorantGaramond-SemiBold.ttf", primary_size)
    ff = load_font("PlusJakartaSans-Bold.ttf", secondary_size)
    kb = d.textbbox((0, 0), "Kratom", font=fk)
    fb = d.textbbox((0, 0), "Feed", font=ff)
    tw = (kb[2] - kb[0]) + int(primary_size * 0.16) + (fb[2] - fb[0])
    th = max(kb[3] - kb[1], fb[3] - fb[1])
    text_pad = int(primary_size * 0.1)
    text_w, text_h = tw + text_pad * 2, th + text_pad * 2

    total_w = padding * 2 + icon_d + gap + text_w
    total_h = padding * 2 + max(icon_d, text_h)
    bg = (0, 0, 0, 0) if transparent else WHITE
    if dark:
        bg = (*PG_DARK, 255)

    canvas = Image.new("RGBA", (total_w, total_h), bg)
    icon_y = padding + (max(icon_d, text_h) - icon_d) // 2
    canvas.paste(icon, (padding, icon_y), icon)

    wordmark = draw_wordmark(text_w, text_h, primary_size, secondary_size, dark=dark)
    canvas.paste(wordmark, (padding + icon_d + gap, padding + (max(icon_d, text_h) - text_h) // 2), wordmark)
    return canvas


def save_outputs() -> None:
    OUT_DIR.mkdir(parents=True, exist_ok=True)

    compose_logo(240, 56, 44, 104, 62).save(OUT_DIR / "kratom-feed-logo.png", optimize=True)
    compose_logo(120, 28, 22, 52, 31).save(OUT_DIR / "kratom-feed-logo-sm.png", optimize=True)
    draw_icon(512).save(OUT_DIR / "kratom-feed-icon.png", optimize=True)
    draw_icon(128).save(OUT_DIR / "kratom-feed-icon-sm.png", optimize=True)
    compose_logo(240, 56, 44, 104, 62, dark=True).save(OUT_DIR / "kratom-feed-logo-dark.png", optimize=True)
    compose_logo(320, 72, 56, 136, 80, transparent=False).convert("RGB").save(
        OUT_DIR / "kratom-feed-logo.pdf", "PDF", resolution=300.0
    )
    print(f"Saved refined logo assets to {OUT_DIR}")


if __name__ == "__main__":
    save_outputs()
