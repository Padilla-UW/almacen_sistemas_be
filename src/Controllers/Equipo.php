<?php

namespace ApiSistemas\Controllers;

use ApiSistemas\Libs\Auth;
use ApiSistemas\Libs\Controller;
use ApiSistemas\Models\EquipoModel;
use ApiSistemas\Models\ExcelModel;
use Mpdf\Mpdf;

class Equipo extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

        $numSerie = (isset($_GET['numSerie']) && $_REQUEST['numSerie'] != NULL) ? $_GET['numSerie'] : '';
        $idTipo = (isset($_GET['idTipo']) && $_REQUEST['idTipo'] != NULL) ? $_GET['idTipo'] : 0;
        $idArea = (isset($_GET['idArea']) && $_REQUEST['idArea'] != NULL) ? $_GET['idArea'] : 0;
        $idPersona = (isset($_GET['idPersona']) && $_REQUEST['idPersona'] != NULL) ? $_GET['idPersona'] : 0;
        $status = (isset($_GET['status']) && $_REQUEST['status'] != NULL) ? $_GET['status'] : '';
        $idUbicacion = (isset($_GET['idUbicacion']) && $_REQUEST['idUbicacion'] != NULL) ? $_GET['idUbicacion'] : '';

        $this->response(["equipos" => EquipoModel::getEquipos($numSerie, $idTipo, $idArea, $idPersona, $status, $idUbicacion)]);
    }

    public function create()
    {
        $this->exists(['idTipo', 'numSerie']);
        $equipo = new EquipoModel();
        $this->data = $_POST;

        $equipo->setIdTipo(($this->data['idTipo']) ? $this->data['idTipo'] : null);
        $equipo->setIdPersona(($this->data['idPersona']) ? $this->data['idPersona'] : null);
        $equipo->setIdProveedor(($this->data['idProveedor']) ? $this->data['idProveedor'] : null);
        $equipo->setMarca(($this->data['marca']) ? $this->data['marca'] : '');
        $equipo->setModelo(($this->data['modelo']) ? $this->data['modelo'] : '');
        $equipo->setNumSerie(($this->data['numSerie']) ? $this->data['numSerie'] : '');
        $equipo->setFechaCompra(($this->data['fechaCompra']) ? $this->data['fechaCompra'] : '');
        $equipo->setNumFactura(($this->data['numFactura']) ? $this->data['numFactura'] : '');
        $equipo->setObservaciones(($this->data['observaciones']) ? $this->data['observaciones'] : '');
        $equipo->setStatus('activo');


        $this->response($equipo->save($this->data));
    }

    public function getTipos()
    {
        $this->response(["tipos" => EquipoModel::getTiposEquipo()]);
    }

    public function getDetalles()
    {
        $idEquipo = (isset($_GET['idEquipo']) && $_REQUEST['idEquipo'] != NULL) ? $_GET['idEquipo'] : '';
        $this->response(["detalles" => EquipoModel::getDetallesEquipo($idEquipo)]);
    }

    public function edit()
    {
        $this->data = $_POST;
        $this->exists(['idEquipo', 'idTipo', 'numSerie']);
        $equipo = new EquipoModel();
        $equipo->setId(($this->data['idEquipo']) ? $this->data['idEquipo'] : null);
        $equipo->setIdTipo(($this->data['idTipo']) ? $this->data['idTipo'] : null);
        $equipo->setIdProveedor(($this->data['idProveedor']) ? $this->data['idProveedor'] : null);
        $equipo->setMarca(($this->data['marca']) ? $this->data['marca'] : '');
        $equipo->setModelo(($this->data['modelo']) ? $this->data['modelo'] : '');
        $equipo->setNumSerie(($this->data['numSerie']) ? $this->data['numSerie'] : '');
        $equipo->setFechaCompra(($this->data['fechaCompra']) ? $this->data['fechaCompra'] : '');
        $equipo->setNumFactura(($this->data['numFactura']) ? $this->data['numFactura'] : '');
        $equipo->setObservaciones(($this->data['observaciones']) ? $this->data['observaciones'] : '');
        $equipo->setStatus(($this->data['status']) ? $this->data['status'] : '');

        $this->response($equipo->edit($this->data));
    }

    public function getQr()
    {
        $idEquipo = (isset($_GET['idEquipo']) && $_REQUEST['idEquipo'] != NULL) ? $_GET['idEquipo'] : '';
        try {
            $img = 'Qrs/' . $idEquipo . '.png';
            if (!file_exists('Qrs/' . $idEquipo . '.png')) {
                $url = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=https://developers.google.com/chart/infographics/docs/qr_codes?hl=es-419';
                file_put_contents($img, file_get_contents($url));
            }
            if ($this->generarQrPdf($idEquipo)) {
                $this->response(array("ok" => true, "qr" => "./pdfs/$idEquipo.png"));
            } else {
                $this->response(array("ok" => false, "error" => "Error al generar PDF"));
            }

            return $img;
        } catch (\Throwable $th) {
            $this->response(array("ok" => false, "msj" => $th->getMessage()));
        }
    }


    public function getExcel()
    {
        $excel = new ExcelModel();
        $this->response(["url" => $excel->getExcel()]);
    }

    public function generarQrPdf($idEquipo)
    {
        try {


            $mpdf = new Mpdf(['format' => [100, 60], 'margin_left' => 2, 'margin_right' => 2, 'margin_top' => 2, 'margin_bottom' => 2, 'margin_header' => 0, 'margin_footer' => 0]);
            $mpdf->WriteHTML('<table>
                                <tr>
                                    <td>
                                        <img class=""  src="./Qrs/' . $idEquipo . '.png">
                                    </td>
                                    <td style="padding-left:10px">
                                        <H1>UWIPES</H1>
                                        <H3>' . str_pad($idEquipo, 4, '0', STR_PAD_LEFT) . '</H3>
                                    </td>
                                </tr>
                            </table>');
            $mpdf->Output('pdfs/' . $idEquipo . '.pdf', 'F');
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
