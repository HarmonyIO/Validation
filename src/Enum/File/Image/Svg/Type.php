<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Enum\File\Image\Svg;

final class Type
{
    public const ANIMATION_ELEMENTS = [
        Element::ANIMATE,
        Element::ANIMATE_COLOR,
        Element::ANIMATE_MOTION,
        Element::ANIMATE_TRANSFORM,
        Element::DISCARD,
        Element::MPATH,
        Element::SET,
    ];

    public const BASIC_SHAPES = [
        Element::CIRCLE,
        Element::ELLIPSE,
        Element::LINE,
        Element::POLYGON,
        Element::POLYLINE,
        Element::RECT,
    ];

    public const CONTAINER_ELEMENTS = [
        Element::A,
        Element::DEFS,
        Element::G,
        Element::MARKER,
        Element::MASK,
        Element::MISSING_GLYPH,
        Element::PATTERN,
        Element::SVG,
        Element::SWITCH,
        Element::SYMBOL,
        Element::UNKNOWN,
    ];

    public const DESCRIPTIVE_ELEMENTS = [
        Element::DESC,
        Element::METADATA,
        Element::TITLE,
    ];

    public const FILTER_PRIMITIVE_ELEMENTS = [
        Element::FE_BLEND,
        Element::FE_COLOR_MATRIX,
        Element::FE_COMPONENT_TRANSFER,
        Element::FE_COMPOSITE,
        Element::FE_CONVOLVE_MATRIX,
        Element::FE_DIFFUSE_LIGHTING,
        Element::FE_DISPLACEMENT_MAP,
        Element::FE_DROP_SHADOW,
        Element::FE_FLOOD,
        Element::FE_FUNC_A,
        Element::FE_FUNC_B,
        Element::FE_FUNC_G,
        Element::FE_FUNC_R,
        Element::FE_GAUSSIAN_BLUR,
        Element::FE_IMAGE,
        Element::FE_MERGE,
        Element::FE_MERGE_NODE,
        Element::FE_MORPHOLOGY,
        Element::FE_OFFSET,
        Element::FE_SPECULAR_LIGHTING,
        Element::FE_TILE,
        Element::FE_TURBULENCE,
    ];

    public const FONT_ELEMENTS = [
        Element::FONT,
        Element::FONT_FACE,
        Element::FONT_FACE_FORMAT,
        Element::FONT_FACE_NAME,
        Element::FONT_FACE_SRC,
        Element::FONT_FACE_URI,
        Element::HKERN,
        Element::VKERN,
    ];

    public const GRADIENT_ELEMENTS = [
        Element::LINEAR_GRADIENT,
        Element::MESHGRADIENT,
        Element::RADIAL_GRADIENT,
        Element::STOP,
    ];

    public const GRAPHIC_ELEMENTS = [
        Element::CIRCLE,
        Element::ELLIPSE,
        Element::IMAGE,
        Element::LINE,
        Element::MESH,
        Element::PATH,
        Element::POLYGON,
        Element::POLYLINE,
        Element::RECT,
        Element::TEXT,
        Element::USE,
    ];

    public const GRAPHICS_REFERENCING_ELEMENTS = [
        Element::MESH,
        Element::USE,
    ];

    public const LIGHT_SOURCE_ELEMENTS = [
        Element::FE_DISTANT_LIGHT,
        Element::FE_POINT_LIGHT,
        Element::FE_SPOT_LIGHT,
    ];

    public const NEVER_RENDERED_ELEMENTS = [
        Element::CLIP_PATH,
        Element::DEFS,
        Element::HATCH,
        Element::LINEAR_GRADIENT,
        Element::MARKER,
        Element::MASK,
        Element::MESHGRADIENT,
        Element::METADATA,
        Element::PATTERN,
        Element::RADIAL_GRADIENT,
        Element::SCRIPT,
        Element::STYLE,
        Element::SYMBOL,
        Element::TITLE,
    ];

    public const PAINT_SERVER_ELEMENTS = [
        Element::HATCH,
        Element::LINEAR_GRADIENT,
        Element::MESHGRADIENT,
        Element::PATTERN,
        Element::RADIAL_GRADIENT,
        Element::SOLIDCOLOR,
    ];

    public const RENDERABLE_ELEMENTS = [
        Element::A,
        Element::CIRCLE,
        Element::ELLIPSE,
        Element::FOREIGN_OBJECT,
        Element::G,
        Element::IMAGE,
        Element::LINE,
        Element::MESH,
        Element::PATH,
        Element::POLYGON,
        Element::POLYLINE.
        Element::RECT,
        Element::SVG,
        Element::SWITCH,
        Element::SYMBOL,
        Element::TEXT,
        Element::TEXT_PATH,
        Element::TSPAN,
        Element::UNKNOWN,
        Element::USE,
    ];

    public const SHAPE_ELEMENTS = [
        Element::CIRCLE,
        Element::ELLIPSE,
        Element::LINE,
        Element::MESH,
        Element::PATH,
        Element::POLYGON,
        Element::POLYLINE,
        Element::RECT,
    ];

    public const STRUCTURAL_ELEMENTS = [
        Element::DEFS,
        Element::G,
        Element::SVG,
        Element::SYMBOL,
        Element::USE,
    ];

    public const TEXT_CONTENT_ELEMENTS = [
        Element::ALT_GLYPH,
        Element::ALT_GLYPH_DEF,
        Element::ALT_GLYPH_ITEM,
        Element::GLYPH,
        Element::GLYPH_REF,
        Element::TEXT_PATH,
        Element::TEXT,
        Element::TREF,
        Element::TSPAN,
    ];

    public const TEXT_CONTENT_CHILD_ELEMENTS = [
        Element::ALT_GLYPH,
        Element::TEXT_PATH,
        Element::TREF,
        Element::TSPAN,
    ];

    public const UNCATEGORIZED_ELEMENTS = [
        Element::CLIP_PATH,
        Element::COLOR_PROFILE,
        Element::CURSOR,
        Element::FILTER,
        Element::FOREIGN_OBJECT,
        Element::HATCHPATH,
        Element::MESHPATCH,
        Element::MESHROW,
        Element::SCRIPT,
        Element::STYLE,
        Element::VIEW,
    ];
}
