<?php

namespace App\Support;

use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

/**
 * Wrapper tipis di atas bacon/bacon-qr-code untuk generate QR code sebagai SVG.
 *
 * Dipakai langsung (bukan lewat package Laravel-specific seperti
 * simplesoftwareio/simple-qrcode) karena package tersebut sering
 * mengalami konflik versi bacon/bacon-qr-code dengan ekosistem Laravel
 * versi terbaru. Dengan memakai bacon/bacon-qr-code secara langsung,
 * helper ini tidak terikat pada versi Laravel manapun sama sekali.
 */
class QrCodeGenerator
{
    /**
     * Generate QR code sebagai markup SVG mentah (siap di-echo langsung
     * di Blade dengan {!! !!}).
     */
    public static function svg(string $data, int $size = 200): string
    {
        $renderer = new ImageRenderer(
            new RendererStyle($size, margin: 1),
            new SvgImageBackEnd()
        );

        $writer = new Writer($renderer);

        return $writer->writeString($data);
    }
}
