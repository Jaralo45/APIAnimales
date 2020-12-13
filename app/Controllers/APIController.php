<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class APIController extends ResourceController
{
    protected $modelName = 'App\Models\ModeloAnimales';
    protected $format    = 'json';

    public function index()
    {
        return $this->respond($this->model->findAll());
    }

    public function insertar(){

        $nombre=$this->request->getPost("nombre");
		$edad=$this->request->getPost("edad");
		$tipoanimal=$this->request->getPost("tipoanimal");
		$descripcion=$this->request->getPost("descripcion");
		$comida=$this->request->getPost("comida");
		$foto=$this->request->getPost("foto");

		$datosEnvio=array(
			"nombre"=>$nombre,
			"edad"=>$edad,
			"tipoanimal"=>$tipoanimal,
			"descripcion"=>$descripcion,
			"comida"=>$comida,
			"foto"=>$foto
        );
        
        //3. Utilizar el atributo this->validate del controlador para validar datos
        if($this->validate('animalPOST')){

            $id=$this->model->insert($datosEnvio);
            return $this->respond($this->model->find($id));

        }else{

            $validation = \Config\Services::validation();
            return $this->respond($validation->getErrors());

        }

    }

    public function eliminar($id){

        $consulta=$this->model->where('id',$id)->delete();
        $filasAfectadas=$consulta->connID->affected_rows;

        if($filasAfectadas==1){
            $mensaje=array("mensaje"=>"Registro eliminado");
            return $this->respond(json_encode($mensaje));
        }else{
            $mensaje=array("mensaje"=>"Revisar el id a eliminar");
            return $this->respond(json_encode($mensaje));
        }
    }

    public function editar($id){

        //1. Recibir los datos desde el cliente
        $datosAEditar=$this->request->getRawInput();

        //2. Depurar arreglo de paso 1 para segmentar la info por variables
        $nombre=$datosAEditar["nombre"];
        $edad=$datosAEditar["edad"];

        //3. Organizar los datos para envio hacia BD
        $datosEnvio=array(
			"nombre"=>$nombre,
            "edad"=>$edad
        );

        //4. Ejecutar la consulta si los datos se validaron correctamente
        if($this->validate('animalPUT')){

            try{

                $this->model->update($id,$datosEnvio);
                return $this->respond($this->model->find($id));

            }catch(\Exception $error){
                echo($error->getMessage());
            }
            

        }else{

            $validation = \Config\Services::validation();
            return $this->respond($validation->getErrors());

        }

    }
    
}