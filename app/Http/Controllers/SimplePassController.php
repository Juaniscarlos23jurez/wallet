<?php

namespace App\Http\Controllers;

use AppleWallet\Passbook\Pass\Pass;
use AppleWallet\Passbook\Pass\Structure;
use AppleWallet\Passbook\Pass\Field;
use AppleWallet\Passbook\Pass\Barcode;
use AppleWallet\Passbook\PassFactory;
use Illuminate\Http\Request;

class SimplePassController extends Controller
{
    public function generate()
    {
        try {
            // Crear una estructura simple
            $structure = new Structure();
            
            // Agregar campos básicos
            $structure->addPrimaryField(new Field('member', 'Miembro', 'Juan Pérez'));
            $structure->addSecondaryField(new Field('level', 'Nivel', 'VIP'));
            
            // Crear el pass
            $pass = new class('123456789', 'Tarjeta de Miembro') extends Pass {
                public function getType(): string
                {
                    return 'generic';
                }
            };
            
            // Configurar el pass con valores por defecto para todas las propiedades
            $pass->setOrganizationName('Mi Empresa')
                 ->setPassTypeIdentifier(config('passbook.pass.type_identifier'))
                 ->setTeamIdentifier(config('passbook.pass.team_identifier'))
                 ->setBackgroundColor('rgb(60, 65, 76)')
                 ->setForegroundColor('rgb(255, 255, 255)')
                 ->setLabelColor('rgb(255, 255, 255)')
                 ->setLogoText('') // Inicializar logoText con cadena vacía
                 ->setStructure($structure);
            
            // Agregar código de barras
            $barcode = new Barcode('PKBarcodeFormatQR', '123456789');
            $pass->setBarcode($barcode);
            
            // Asegurarse de que el directorio existe
            $passPath = storage_path('app/passes');
            if (!file_exists($passPath)) {
                mkdir($passPath, 0755, true);
            }

            // Obtener las rutas de los certificados
            $p12Path = base_path(config('passbook.certificates.p12.path'));
            $wwdrPath = base_path(config('passbook.certificates.wwdr.path'));

            // Verificar si los certificados existen
            if (!file_exists($p12Path)) {
                throw new \Exception("El certificado P12 no existe en: " . $p12Path);
            }
            if (!file_exists($wwdrPath)) {
                throw new \Exception("El certificado WWDR no existe en: " . $wwdrPath);
            }
            
            // Crear el PassFactory
            $factory = new PassFactory(
                config('passbook.pass.type_identifier'),
                config('passbook.pass.team_identifier'),
                config('passbook.pass.organization_name'),
                $p12Path,
                config('passbook.certificates.p12.password'),
                $wwdrPath
            );
            
            // Establecer el directorio de salida
            $factory->setOutputPath($passPath);
            
            // Generar el archivo .pkpass
            $pkpassFile = $factory->package($pass);
            
            return response()->download($pkpassFile);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'certificate_paths' => [
                    'p12' => base_path(config('passbook.certificates.p12.path')),
                    'wwdr' => base_path(config('passbook.certificates.wwdr.path'))
                ]
            ], 500);
        }
    }

    public function generatePass()
    {
        try {
            // Crear una clase concreta que extienda de Pass
            $pass = new class('123456789', 'Tarjeta de Miembro') extends \AppleWallet\Passbook\Pass\Pass {
                public function getType(): string
                {
                    return 'generic';
                }
            };

            $pass->setOrganizationName('Mondou')
                ->setPassTypeIdentifier('pass.mondou')
                ->setTeamIdentifier('952MB82CWX')
                ->setBackgroundColor('rgb(60, 65, 76)')
                ->setForegroundColor('rgb(255, 255, 255)')
                ->setLabelColor('rgb(255, 255, 255)')
                ->setLogoText('');

            // Crear el código de barras correctamente
            $barcode = new \AppleWallet\Passbook\Pass\Barcode(
                'PKBarcodeFormatQR',
                '123456789',
                'iso-8859-1'
            );
            $pass->setBarcode($barcode);

            // Crear la estructura y agregar los campos
            $structure = new \AppleWallet\Passbook\Pass\Structure();
            
            // Agregar campo primario
            $primaryField = new \AppleWallet\Passbook\Pass\Field('member', 'Miembro', 'Miembro');
            $structure->addPrimaryField($primaryField);
            
            // Agregar campo secundario
            $secondaryField = new \AppleWallet\Passbook\Pass\Field('level', 'Nivel', 'Nivel');
            $structure->addSecondaryField($secondaryField);
            
            // Establecer la estructura en el pass
            $pass->setStructure($structure);

            // Configurar las imágenes usando la clase Image
            $imagesPath = storage_path('app/passes/images');
            
            // Crear directorio temporal para las imágenes
            $tempPath = storage_path('app/passes/temp');
            if (!file_exists($tempPath)) {
                mkdir($tempPath, 0755, true);
            }

            // Copiar las imágenes al directorio temporal
            $images = [
                'icon.png' => 'icon.png',
                'icon@2x.png' => 'icon@2x.png',
                'logo.png' => 'logo.png',
                'logo@2x.png' => 'logo@2x.png',
                'background.png' => 'strip.png',
                'background@2x.png' => 'strip@2x.png',
                'thumbnail.png' => 'thumbnail.png',
                'thumbnail@2x.png' => 'thumbnail@2x.png'
            ];

            foreach ($images as $source => $target) {
                $sourcePath = $imagesPath . '/' . $source;
                $targetPath = $tempPath . '/' . $target;
                
                if (!file_exists($sourcePath)) {
                    throw new \Exception("La imagen {$source} no existe en {$sourcePath}");
                }
                
                if (!copy($sourcePath, $targetPath)) {
                    throw new \Exception("No se pudo copiar la imagen {$source} a {$targetPath}");
                }
            }

            // Verificar que las imágenes se copiaron correctamente
            $tempImages = glob($tempPath . '/*.png');
            if (count($tempImages) !== count($images)) {
                throw new \Exception("No todas las imágenes se copiaron correctamente. Se encontraron " . count($tempImages) . " de " . count($images) . " imágenes.");
            }

            // Agregar las imágenes al pass
            $pass->addImage(new \AppleWallet\Passbook\Pass\Image($tempPath . '/icon.png', \AppleWallet\Passbook\Pass\Image::TYPE_ICON))
                ->addImage(new \AppleWallet\Passbook\Pass\Image($tempPath . '/icon@2x.png', \AppleWallet\Passbook\Pass\Image::TYPE_ICON_RETINA))
                ->addImage(new \AppleWallet\Passbook\Pass\Image($tempPath . '/logo.png', \AppleWallet\Passbook\Pass\Image::TYPE_LOGO))
                ->addImage(new \AppleWallet\Passbook\Pass\Image($tempPath . '/logo@2x.png', \AppleWallet\Passbook\Pass\Image::TYPE_LOGO_RETINA))
                ->addImage(new \AppleWallet\Passbook\Pass\Image($tempPath . '/strip.png', \AppleWallet\Passbook\Pass\Image::TYPE_STRIP))
                ->addImage(new \AppleWallet\Passbook\Pass\Image($tempPath . '/strip@2x.png', \AppleWallet\Passbook\Pass\Image::TYPE_STRIP_RETINA))
                ->addImage(new \AppleWallet\Passbook\Pass\Image($tempPath . '/thumbnail.png', \AppleWallet\Passbook\Pass\Image::TYPE_THUMBNAIL))
                ->addImage(new \AppleWallet\Passbook\Pass\Image($tempPath . '/thumbnail@2x.png', \AppleWallet\Passbook\Pass\Image::TYPE_THUMBNAIL_RETINA));

            // Crear el factory y generar el pass
            $factory = new \AppleWallet\Passbook\PassFactory(
                config('passbook.pass.type_identifier'),
                config('passbook.pass.team_identifier'),
                config('passbook.pass.organization_name'),
                base_path(config('passbook.certificates.p12.path')),
                config('passbook.certificates.p12.password'),
                base_path(config('passbook.certificates.wwdr.path'))
            );

            $factory->setOutputPath(storage_path('app/passes'));
            $pkpassFile = $factory->package($pass);

            // Verificar que el archivo .pkpass se generó correctamente
            if (!file_exists($pkpassFile)) {
                throw new \Exception("El archivo .pkpass no se generó correctamente");
            }

            // Verificar el contenido del archivo .pkpass
            $zip = new \ZipArchive();
            if ($zip->open($pkpassFile) === true) {
                $files = [];
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $files[] = $zip->getNameIndex($i);
                }
                $zip->close();
                
                if (!in_array('icon.png', $files) || !in_array('logo.png', $files) || !in_array('strip.png', $files)) {
                    throw new \Exception("Las imágenes no se incluyeron en el archivo .pkpass");
                }
            }

            // Limpiar el directorio temporal
            array_map('unlink', glob($tempPath . '/*'));
            rmdir($tempPath);

            return response()->download($pkpassFile, 'pass.pkpass', [
                'Content-Type' => 'application/vnd.apple.pkpass',
                'Content-Disposition' => 'attachment; filename="pass.pkpass"',
                'Content-Length' => filesize($pkpassFile),
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function showDownloadPage()
    {
        return view('passbook.download');
    }
} 