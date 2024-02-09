<?php

require_once __DIR__ . '/../Connection/DbManager.php';

class Product
{
    private $id;
    private $nome;
    private $prezzo;
    private $marca;


    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getPrezzo()
    {
        return $this->prezzo;
    }

    public function setPrezzo($prezzo)
    {
        $this->prezzo = $prezzo;
    }

    public function getMarca()
    {
        return $this->marca;
    }

    public function setMarca($marca)
    {
        $this->marca = $marca;
    }

    public static function Find($id)
    {
        $pdo = self::Connect();
        $stmt = $pdo->prepare("select * from nicolo_bertoncello_ecommerce.products where id = :id");
        $stmt->bindParam(":id", $id);
        if ($stmt->execute()) {
            return $stmt->fetchObject("Product");
        } else {
            throw new PDOException("Prodotto non trovato");
        }
    }

    public static function Create($params)
    {
        $pdo = self::Connect();
        $stmt = $pdo->prepare("insert into nicolo_bertoncello_ecommerce.products (nome,prezzo,marca) values (:nome,:prezzo,:marca)");
        $stmt->bindParam(":nome", $params["nome"]);
        $stmt->bindParam(":prezzo", $params["prezzo"]);
        $stmt->bindParam(":marca", $params["marca"]);
        if ($stmt->execute()) {
            $stmt = $pdo->prepare("select * from nicolo_bertoncello_ecommerce.products order by id desc limit 1");
            $stmt->execute();
            return $stmt->fetchObject("Product");
        } else {
            throw new PDOException("Errore Nella Creazione");
        }
    }

    public static function FetchAll()
    {
        $pdo = self::Connect();
        $stmt = $pdo->query("select * from nicolo_bertoncello_ecommerce.products");
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Product');

    }

    public static function duplicato($params)
    {
        $pdo = self::Connect();
        $stmt = $pdo->prepare("select * from nicolo_bertoncello_ecommerce.products where nome = :nome and marca = :marca and prezzo = :prezzo");
        $stmt->bindParam(":nome", $params["nome"]);
        $stmt->bindParam(":marca", $params["marca"]);
        $stmt->bindParam(":prezzo", $params["prezzo"]);
        if ($stmt->execute()) {
            return $stmt->fetchObject("Product");
        } else {
            throw new PDOException("Prodotto non trovato");
        }
    }

    public function update($nome, $marca, $prezzo)
    {
        $id = $this->getId();
        $pdo = self::Connect();
        $stmt = $pdo->prepare("update nicolo_bertoncello_ecommerce.products set nome = :nome , marca = :marca , prezzo = :prezzo where id = :id ");
        $stmt->bindParam(":nome", $nome);
        $stmt->bindParam(":marca", $marca);
        $stmt->bindParam(":prezzo", $prezzo);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
    }

    public static function Connect()
    {
        return DbManager::Connect("nicolo_bertoncello_ecommerce");
    }

    public function delete()
    {

        $pdo = self::Connect();
        $id = $this->getId();
        $stmt = $pdo->prepare("delete from nicolo_bertoncello_ecommerce.products where id = :id");
        $stmt->bindParam(":id", $id);
        if (!$stmt->execute()) {
            throw new PDOException("impossibile cancellare il record");
        }


    }


}
