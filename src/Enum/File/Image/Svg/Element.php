<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Enum\File\Image\Svg;

final class Element
{
    public const A = 'a';
    public const ALT_GLYPH = 'altGlyph';
    public const ALT_GLYPH_DEF = 'altGlyphDef';
    public const ALT_GLYPH_ITEM = 'altGlyphItem';
    public const ANIMATE = 'animate';
    public const ANIMATE_COLOR = 'animateColor';
    public const ANIMATE_MOTION = 'animateMotion';
    public const ANIMATE_TRANSFORM = 'animateTransform';
    public const CIRCLE = 'circle';
    public const CLIP_PATH = 'clipPath';
    public const COLOR_PROFILE = 'color-profile';
    public const CURSOR = 'cursor';
    public const DEFS = 'defs';
    public const DESC = 'desc';
    public const DISCARD = 'discard';
    public const ELLIPSE = 'ellipse';
    public const FE_BLEND = 'feBlend';
    public const FE_COLOR_MATRIX = 'feColorMatrix';
    public const FE_COMPONENT_TRANSFER = 'feComponentTransfer';
    public const FE_COMPOSITE = 'feComposite';
    public const FE_CONVOLVE_MATRIX = 'feConvolveMatrix';
    public const FE_DIFFUSE_LIGHTING = 'feDiffuseLighting';
    public const FE_DISPLACEMENT_MAP = 'feDisplacementMap';
    public const FE_DISTANT_LIGHT = 'feDistantLight';
    public const FE_DROP_SHADOW = 'feDropShadow';
    public const FE_FLOOD = 'feFlood';
    public const FE_FUNC_A = 'feFuncA';
    public const FE_FUNC_B = 'feFuncB';
    public const FE_FUNC_G = 'feFuncG';
    public const FE_FUNC_R = 'feFuncR';
    public const FE_GAUSSIAN_BLUR = 'feGaussianBlur';
    public const FE_IMAGE = 'feImage';
    public const FE_MERGE = 'feMerge';
    public const FE_MERGE_NODE = 'feMergeNode';
    public const FE_MORPHOLOGY = 'feMorphology';
    public const FE_OFFSET = 'feOffset';
    public const FE_POINT_LIGHT = 'fePointLight';
    public const FE_SPECULAR_LIGHTING = 'feSpecularLighting';
    public const FE_SPOT_LIGHT = 'feSpotLight';
    public const FE_TILE = 'feTile';
    public const FE_TURBULENCE = 'feTurbulence';
    public const FILTER = 'filter';
    public const FONT = 'font';
    public const FONT_FACE = 'font-face';
    public const FONT_FACE_FORMAT = 'font-face-format';
    public const FONT_FACE_NAME = 'font-face-name';
    public const FONT_FACE_SRC = 'font-face-src';
    public const FONT_FACE_URI = 'font-face-uri';
    public const FOREIGN_OBJECT = 'foreignObject';
    public const G = 'G';
    public const GLYPH = 'glyph';
    public const GLYPH_REF = 'glyphRef';
    public const HATCH = 'hatch';
    public const HATCHPATH = 'hatchpath';
    public const HKERN = 'hkern';
    public const IMAGE = 'image';
    public const LINE = 'line';
    public const LINEAR_GRADIENT = 'linearGradient';
    public const MARKER = 'marker';
    public const MASK = 'mask';
    public const MESH = 'mesh';
    public const MESHGRADIENT = 'meshgradient';
    public const MESHPATCH = 'meshpatch';
    public const MESHROW = 'meshrow';
    public const METADATA = 'metadata';
    public const MISSING_GLYPH = 'missing-glyph';
    public const MPATH = 'mpath';
    public const PATH = 'path';
    public const PATTERN = 'pattern';
    public const POLYGON = 'polygon';
    public const POLYLINE = 'polyline';
    public const RADIAL_GRADIENT = 'radialGradient';
    public const RECT = 'rect';
    public const SCRIPT = 'script';
    public const SET = 'set';
    public const SOLIDCOLOR = 'solidcolor';
    public const STOP = 'stop';
    public const STYLE = 'style';
    public const SVG = 'svg';
    public const SWITCH = 'switch';
    public const SYMBOL = 'symbol';
    public const TEXT = 'text';
    public const TEXT_PATH = 'textPath';
    public const TITLE = 'title';
    public const TREF = 'tref';
    public const TSPAN = 'tspan';
    public const UNKNOWN = 'unknown';
    public const USE = 'use';
    public const VIEW = 'view';
    public const VKERN = 'vkern';

    public function exists(string $value): bool
    {
        return in_array($value, (new \ReflectionClass(self::class))->getConstants(), true);
    }
}
