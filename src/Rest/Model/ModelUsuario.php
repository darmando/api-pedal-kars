<?php
require "../src/Rest/Bean/Usuario.php";
class ModelUsuario extends Usuario{
    public $db;
    private $response;
    public function __construct($conn) {
        $this->db = $conn;
        $this->response = array();
    }

    public function login($usuario,$contrasena) {
        $sql = "SELECT * FROM usuario AS US WHERE US.status=1 AND US.usuario= '$usuario' AND US.contrasena= '$contrasena'";
        try {
            $db   = $this->db;
            $stmt = $db->query($sql); 
            $rs =  $stmt->fetchAll(PDO::FETCH_CLASS, "Usuario");
            $db = null; 
            if(!empty($rs)){
                $this->response['exists']   = true;
                $this->response['data']    = $rs[0];
                $this->response['error']   = false; 
                $this->response['message'] = "success"; 
                $this->response['changePassword'] = $rs[0]->contrasena == '7c4a8d09ca3762af61e59520943dc26494f8941b' ? true : false;
            }else{
                $this->response['exists']   = false;
                $this->response['data']    = [];
                $this->response['error']   = false; 
                $this->response['message'] = "Usuario o contraseña incorrectos"; 
                $this->response['changePassword'] = false;

            }    
            
        } catch(PDOException $e) {
            $this->response['exists']   = false;
            $this->response['data']    = null;
            $this->response['error']   = true; 
            $this->response['changePassword'] = false;
            $this->response['message'] = $e->getMessage(); 
        }
        return $this->response;
    }




    public function getUsuarios(){
        try {
            $sql  = "SELECT * FROM usuario as usu where usu.status=1 order by id_usuario desc";
            $db   = $this->db;
            $stmt = $db->query($sql); 
            $rs   =  $stmt->fetchAll(PDO::FETCH_CLASS, "Usuario");
            $db   = null;
            if(!empty($rs)){
                $this->response['data'] = $rs;
                $this->response['error'] = false; 
                $this->response['message'] = "success";  
            }else{
                $this->response['data'] = [];
                $this->response['error'] = false; 
                $this->response['message'] = "Sin Registros en la Base de Datos.";  
            }
           
        } catch(PDOException $e) {
            $this->response['data'] = [];
            $this->response['error'] = true; 
            $this->response['message'] = $e->getMessage(); 
        }
        return $this->response;
    }

    public function resetPsw($id_usuario,$tmpPsw) {
            $sql = "UPDATE `usuario`
                    SET
                    `contrasena`= :tmpPsw
                    WHERE `id_usuario` = :id_usuario";
        try {
            $db   = $this->db;
            $stmt = $db->prepare($sql); 
            $stmt->bindParam("id_usuario", $id_usuario);
            $stmt->bindParam("tmpPsw", $tmpPsw );
            $stmt->execute();
            $db = null;     
            $this->response['data'] = $stmt->rowCount() ? 1 : 0;
            $this->response['error'] = false; 
            $this->response['message'] = "Contraseña reseteada por '123456', cuando el usuario ingrese pedira ingresar una nueva.";         
        } catch(PDOException $e) {
            $this->response['data'] = null;
            $this->response['error'] = false; 
            $this->response['message'] = "Ocurrio un error, vuelva a intentar.".$e->getMessage();    
        }
         return $this->response;
    }

    public function cambiarPsw($id_usuario,$contrasena) {
            $sql = "UPDATE `usuario`
                    SET
                    `contrasena`= :contrasena
                    WHERE `id_usuario` = :id_usuario";
        try {
            $db   = $this->db;
            $stmt = $db->prepare($sql); 
            $stmt->bindParam("id_usuario", $id_usuario);
            $stmt->bindParam("contrasena", $contrasena );
            $stmt->execute();
            $db = null;     
            $this->response['data'] = $stmt->rowCount() ? 1 : 0;
            $this->response['error'] = false; 
            $this->response['message'] = "Contraseña cambiada correctamente.";         
        } catch(PDOException $e) {
            $this->response['data'] = null;
            $this->response['error'] = false; 
            $this->response['message'] = "Ocurrio un error, vuelva a intentar.".$e->getMessage();    
        }
         return $this->response;
    }


    public function saveUser(Usuario $usuario) {
        $sql = "INSERT INTO `usuario`
                (`nombre`,
                `usuario`,
                `contrasena`,
                `status`,
                `fecha_alta`)
                VALUES
                (:nombre,
                :usuario,
                :contrasena,
                :status,
                :fecha_alta)";
        try {
            $db   = $this->db;
            $stmt = $db->prepare($sql); 
            $stmt->bindValue("nombre",  $usuario->getNombre());
            $stmt->bindValue("usuario",  $usuario->getUsuario());
            $stmt->bindValue("contrasena", $usuario->getContrasena() );
            $stmt->bindValue("status", $usuario->getStatus());
            $stmt->bindValue("fecha_alta", $usuario->getFechaAlta());
            $stmt->execute();
            $id = $db->lastInsertId();
            $usuario->setIdUsuario($id);
            $lastInsertId = $id > 0 ? $id : 0;
            $db = null;     
            $this->response['data'] = $usuario;
            $this->response['error'] = false; 
            $this->response['message'] = "Usuario '".$usuario->getNombre()."' creado con éxito.";             
        } catch(PDOException $e) {
            $this->response['data'] = null;
            $this->response['error'] = true; 
            $this->response['message'] = "Ocurrio un error, vuelva a intentar.".$e->getMessage();    
        }
        return $this->response;
    }

   
    public function editUser(Usuario $usuario) {
        $sql = "UPDATE `usuario`
                SET
                `nombre`     = :nombre,
                `usuario`    = :usuario,
                `status`     = :status
                WHERE `id_usuario` = :id_usuario";   
        try {
            $db   = $this->db;
            $stmt = $db->prepare($sql); 
            $stmt->bindParam("nombre",  $usuario->getNombre());
            $stmt->bindParam("usuario",  $usuario->getUsuario());
            $stmt->bindParam("status", $usuario->getStatus());
            $stmt->bindParam("id_usuario", $usuario->getIdUsuario());
            $stmt->execute();
            $db = null;     
            $this->response['data'] = $stmt->rowCount() ? 1 : 0;
            $this->response['error'] = false; 
            $this->response['message'] = "Usuario '".$usuario->getUsuario()."' actualizado con éxito.";         
        } catch(PDOException $e) {
            $this->response['data'] = null;
            $this->response['error'] = false; 
            $this->response['message'] = "Ocurrio un error, vuelva a intentar.";    
        }
         return $this->response;
    }



    public function deleteUser(User $user) {
        $sql = "UPDATE `usuario`
                SET
                `status`     = :status,
                WHERE `id_usuario` = :id_usuario}>;
                ";
        try {
            $db = $this->conn->getConnection();
            $stmt = $db->prepare($sql); 
            $stmt->bindParam("id_user",$user->getIdUser());
            $stmt->bindParam("status", $usuario->getStatus());
            $stmt->execute();
            $response  = $stmt->rowCount() ? 1 : 0;
            $db = null;     
            return $response;
        } catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}'; 
        }
    }


}

?>
