<?php

namespace ApiSistemas\Models;

use ApiSistemas\Libs\Controller;
use ApiSistemas\Libs\Model;
use PDO;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class ExcelModel extends Model
{
    public $idTipo;
    public $data;
    public function __construct()
    {
        parent::__construct();
    }



    public function getExcel()
    {

        try {
            $spreadsheet = new Spreadsheet();
            $writer = new Xlsx($spreadsheet);


            $queryTipo = $this->query("SELECT * FROM tipo_equipo");
            $contadorHoja = 1;
            while ($r = $queryTipo->fetch(PDO::FETCH_ASSOC)) {
                $spreadsheet->setActiveSheetIndex($spreadsheet->getSheetCount() - 1);
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setTitle(strtoupper($r['tipo']));

                $tabla = $r['tabla'];

                $this->idTipo = $r['idTipo'];
                $query = $this->query("SELECT e.*, $tabla.*, u.ubicacion,a.area,  MAX(t.fecha) AS fechaTraspaso, CONCAT(p.nombre,' ',p.apellidos) AS usuario, n.nivel, p.nivelNum, CONCAT(p2.nombre,' ',p2.apellidos) AS responsable, CONCAT(p3.nombre, ' ', p3.apellidos) AS personaAnterior, CONCAT(pro.nombre, ' ', pro.apellidos) AS proveedor
                FROM equipo e 
                INNER JOIN $tabla ON e.idEquipo = $tabla.idEquipo 
                INNER JOIN persona p ON p.idPersona = e.idPersona 
                INNER JOIN ubicacion_persona u ON u.idUbicacion = p.idUbicacion
                INNER JOIN area_persona a ON a.idArea = p.idArea
                INNER JOIN nivel_persona n ON p.idNivel = n.idNivel
                LEFT JOIN proveedor pro ON e.idProveedor = pro.idProveedor
                LEFT JOIN persona p2 ON p.idResponsable = p2.idPersona
                LEFT JOIN traspaso t ON t.idEquipo = e.idEquipo
                LEFT JOIN persona p3 ON t.idPersonaOrigen = p3.idPersona
                GROUP BY e.idEquipo");
                $sheet->fromArray($this->headExcel(), NULL, 'A2');
                $this->setStyleHead($sheet);
                $c = 3;
                while ($r = $query->fetch(PDO::FETCH_ASSOC)) {
                    $this->data = $r;
                    $sheet->fromArray($this->rowEquipo(), NULL, "A$c");
                    $c++;
                }

                if ($queryTipo->rowCount() > $contadorHoja) {
                    $spreadsheet->createSheet();

                    $contadorHoja++;
                }
                $this->setStyleSheet($sheet);
            }

            $spreadsheet->setActiveSheetIndex(0);
            $writer->save('archivoDinamico.xlsx');
            return [];
        } catch (\Throwable $th) {
            echo $th->getMessage();
            return [];
        }
    }

    public function headExcel()
    {
        switch ($this->idTipo) {
            case 1:
                return ['UBICACIÓN', 'AREA', 'USUARIO', 'NIVEL', 'NIVEL NUM', 'RESPONSABLE', 'TIPO', 'MAC ADDRESS', 'MARCA DEL EQUIPO', 'MODELO', 'NO. DE PARTE', 'PROCESADOR', 'BENCHMARKING PROCESADOR', 'LIGA BENCH', 'VALUACION', 'AÑO', 'RAM ORIGINAL', 'EXPANSION', 'RAM TOTAL', 'TARJETA MADRE', 'DISCO DURO', 'NO. SERIE', 'WINDOWS', 'LUGAR', 'CERTIFICADO', 'VERSION DE OFFICE', 'TARJETA DE VIDEO', 'OTRO SOFTWARE', 'USUARIO ANTERIOR ', 'FECHA DE TRASPASO', 'FECHA DE COMPRA', 'FECHA DE RENOVACION', 'PRECIO', 'VALOR DEPRECIADO', 'MERCADO', 'NO. FACT', 'PROVEEDOR', 'RESPONSIVA', 'ESTADO', 'FIRMA', 'FIRMA', 'OBSERVACIONES'];
                break;
            case 2:
                return ['UBICACIÓN', 'AREA', 'USUARIO', 'NIVEL', 'NIVEL NUM', 'RESPONSABLE','MARCA DEL EQUIPO', 'MODELO', 'NO. SERIE', 'USUARIO ANTERIOR ', 'FECHA DE TRASPASO', 'FECHA DE COMPRA', 'NO. FACT', 'PROVEEDOR', 'RESPONSIVA', 'ESTADO', 'FIRMA', 'FIRMA', 'OBSERVACIONES', 'PULGADAS'];
                break;
            case 3:
                return ['UBICACIÓN', 'AREA', 'USUARIO', 'NIVEL', 'NIVEL NUM', 'RESPONSABLE','MARCA DEL EQUIPO', 'MODELO', 'NO. SERIE', 'USUARIO ANTERIOR ', 'FECHA DE TRASPASO', 'FECHA DE COMPRA', 'NO. FACT', 'PROVEEDOR', 'RESPONSIVA', 'ESTADO', 'FIRMA', 'FIRMA', 'OBSERVACIONES', 'NUMERO DE CELULAR', 'FECHA DE INICIO', 'FECHA DE FIN'];
                break;
            case 4:
                return ['UBICACIÓN', 'AREA', 'USUARIO', 'NIVEL', 'NIVEL NUM', 'RESPONSABLE','MARCA DEL EQUIPO', 'MODELO', 'NO. SERIE', 'USUARIO ANTERIOR ', 'FECHA DE TRASPASO', 'FECHA DE COMPRA', 'NO. FACT', 'PROVEEDOR', 'RESPONSIVA', 'ESTADO', 'FIRMA', 'FIRMA', 'OBSERVACIONES', 'TIPO DE CHECADA', 'IP'];
                break;
            case 5:
                return ['UBICACIÓN', 'AREA', 'USUARIO', 'NIVEL', 'NIVEL NUM', 'RESPONSABLE','MARCA DEL EQUIPO', 'MODELO', 'NO. SERIE', 'USUARIO ANTERIOR ', 'FECHA DE TRASPASO', 'FECHA DE COMPRA', 'NO. FACT', 'PROVEEDOR', 'RESPONSIVA', 'ESTADO', 'FIRMA', 'FIRMA', 'OBSERVACIONES', 'CAPACIDAD'];
                break;
            case 6:
                return ['UBICACIÓN', 'AREA', 'USUARIO', 'NIVEL', 'NIVEL NUM', 'RESPONSABLE','MARCA DEL EQUIPO', 'MODELO', 'NO. SERIE', 'USUARIO ANTERIOR ', 'FECHA DE TRASPASO', 'FECHA DE COMPRA', 'NO. FACT', 'PROVEEDOR', 'RESPONSIVA', 'ESTADO', 'FIRMA', 'FIRMA', 'OBSERVACIONES', 'IMPRESIONES POR MES'];
                break;
            case 7:
                return ['UBICACIÓN', 'AREA', 'USUARIO', 'NIVEL', 'NIVEL NUM', 'RESPONSABLE','MARCA DEL EQUIPO', 'MODELO', 'NO. SERIE', 'USUARIO ANTERIOR ', 'FECHA DE TRASPASO', 'FECHA DE COMPRA', 'NO. FACT', 'PROVEEDOR', 'RESPONSIVA', 'ESTADO', 'FIRMA', 'FIRMA', 'OBSERVACIONES'];
                break;
            case 8:
                return ['UBICACIÓN', 'AREA', 'USUARIO', 'NIVEL', 'NIVEL NUM', 'RESPONSABLE','MARCA DEL EQUIPO', 'MODELO', 'NO. SERIE', 'USUARIO ANTERIOR ', 'FECHA DE TRASPASO', 'FECHA DE COMPRA', 'NO. FACT', 'PROVEEDOR', 'RESPONSIVA', 'ESTADO', 'FIRMA', 'FIRMA', 'OBSERVACIONES'];
                break;
            case 9:
                return ['UBICACIÓN', 'AREA', 'USUARIO', 'NIVEL', 'NIVEL NUM', 'RESPONSABLE','MARCA DEL EQUIPO', 'MODELO', 'NO. SERIE', 'USUARIO ANTERIOR ', 'FECHA DE TRASPASO', 'FECHA DE COMPRA', 'NO. FACT', 'PROVEEDOR', 'RESPONSIVA', 'ESTADO', 'FIRMA', 'FIRMA', 'OBSERVACIONES', 'TAMAÑO'];
                break;
            case 10:
                return ['UBICACIÓN', 'AREA', 'USUARIO', 'NIVEL', 'NIVEL NUM', 'RESPONSABLE','MARCA DEL EQUIPO', 'MODELO', 'NO. SERIE', 'USUARIO ANTERIOR ', 'FECHA DE TRASPASO', 'FECHA DE COMPRA', 'NO. FACT', 'PROVEEDOR', 'RESPONSIVA', 'ESTADO', 'FIRMA', 'FIRMA', 'OBSERVACIONES'];
                break;
            default:
                return [];
                break;
        }
    }

    public function rowEquipo()
    {
        switch ($this->idTipo) {
            case 1:
                return $this->rowCPU();
                break;
            case 2:
                return $this->rowMonitor();
                break;
            case 3:
                return $this->rowCelular();
                break;
            case 4:
                return $this->rowChecador();
                break;
            case 5:
                return $this->rowDisco();
                break;
            case 6:
                return $this->rowImpresora();
                break;
            case 7:
                return $this->rowNoBrake();
                break;
            case 8:
                return $this->rowProyector();
                break;
            case 9:
                return $this->rowSmart();
                break;
            case 10:
                return $this->rowTablet();
                break;
            default:
                return [];
                break;
        }
    }

    public function rowCPU()
    {
        extract($this->data);
        return [$ubicacion, $area, $usuario, $nivel, $nivelNum, $responsable, $tipo, $macAddress, $marca, $modelo, $numParte, $procesador, $benchmark, $ligaBenchmark, $valuacion, $year, $ram, $expancionRam, "", $tarjetaMadre, $almacenamiento, $numSerie, $sistemaOperativo, $lugar, $certificado, $versionOffice, $tarjetaVideo, $otroSotfware, $personaAnterior, $fechaTraspaso, $fechaCompra, $fechaRenovacion, $precio, $valorDepreciado, $precioMercado, $numFactura, $proveedor, $responsiva, $status, "", $firma, $observaciones];
    }
    
    public function rowMonitor()
    {
        extract($this->data);
        return[$ubicacion, $area, $usuario, $nivel, $nivelNum, $responsable, $marca, $modelo,$numSerie,$personaAnterior, $fechaTraspaso, $fechaCompra,$numFactura, $proveedor, $responsiva, $status, "", $firma, $observaciones, $pulgadas];
    }

    public function rowCelular()
    {
        extract($this->data);
        return[$ubicacion, $area, $usuario, $nivel, $nivelNum, $responsable, $marca, $modelo,$numSerie,$personaAnterior, $fechaTraspaso, $fechaCompra,$numFactura, $proveedor, $responsiva, $status, "", $firma, $observaciones, $numCelular, $fechaInicio, $fechaFin];
    }

    public function rowChecador()
    {
        extract($this->data);
        return[$ubicacion, $area, $usuario, $nivel, $nivelNum, $responsable, $marca, $modelo,$numSerie,$personaAnterior, $fechaTraspaso, $fechaCompra,$numFactura, $proveedor, $responsiva, $status, "", $firma, $observaciones, $tipoChecada, $ip];
    }

    public function rowDisco()
    {
        extract($this->data);
        return[$ubicacion, $area, $usuario, $nivel, $nivelNum, $responsable, $marca, $modelo,$numSerie,$personaAnterior, $fechaTraspaso, $fechaCompra,$numFactura, $proveedor, $responsiva, $status, "", $firma, $observaciones, $capacidad];
    }

    public function rowImpresora()
    {
        extract($this->data);
        return[$ubicacion, $area, $usuario, $nivel, $nivelNum, $responsable, $marca, $modelo,$numSerie,$personaAnterior, $fechaTraspaso, $fechaCompra,$numFactura, $proveedor, $responsiva, $status, "", $firma, $observaciones, $impresionesXMes];
    }

    public function rowNoBrake()
    {
        extract($this->data);
        return[$ubicacion, $area, $usuario, $nivel, $nivelNum, $responsable, $marca, $modelo,$numSerie,$personaAnterior, $fechaTraspaso, $fechaCompra,$numFactura, $proveedor, $responsiva, $status, "", $firma, $observaciones,];
    }

    public function rowProyector()
    {
        extract($this->data);
        return[$ubicacion, $area, $usuario, $nivel, $nivelNum, $responsable, $marca, $modelo,$numSerie,$personaAnterior, $fechaTraspaso, $fechaCompra,$numFactura, $proveedor, $responsiva, $status, "", $firma, $observaciones,];
    }

    public function rowSmart()
    {
        extract($this->data);
        return[$ubicacion, $area, $usuario, $nivel, $nivelNum, $responsable, $marca, $modelo,$numSerie,$personaAnterior, $fechaTraspaso, $fechaCompra,$numFactura, $proveedor, $responsiva, $status, "", $firma, $observaciones, $size];
    }

    public function rowTablet()
    {
        extract($this->data);
        return[$ubicacion, $area, $usuario, $nivel, $nivelNum, $responsable, $marca, $modelo,$numSerie,$personaAnterior, $fechaTraspaso, $fechaCompra,$numFactura, $proveedor, $responsiva, $status, "", $firma, $observaciones,];
    }

    public function setStyleHead($sheet)
    {
        $maxCol = $sheet->getHighestColumn() . "2";
        $sheet->getStyle("A2:$maxCol")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle("A2:$maxCol")->getFill()->getStartColor()->setARGB('FFACB9CA');
        $sheet->getStyle("A2:$maxCol")->getFont()->setBold(true);
    }

    public function setStyleSheet($sheet)
    {
        $maxCol = $sheet->getHighestColumn();
        $maxRow = $sheet->getHighestRow();
        $sheet->getStyle("A2:$maxCol$maxRow")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        foreach ($sheet->getColumnIterator() as $column) {
            $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
        }
    }
}
