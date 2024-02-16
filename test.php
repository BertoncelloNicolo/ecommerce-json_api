<?php
require_once __DIR__ . '/Models/Product.php';
require_once __DIR__ . '/Connection/DbManager.php';

// Funzione per ottenere l'ID dall'URL
function getIdFromUrl($url)
{
    $parts = explode('/', $url);
    return end($parts);
}

// Funzione per gestire le richieste
function handleRequest()
{
    $method = getRequestMethod();
    $path = getRequestPath();

    switch ($method) {
        case 'GET':
            if ($path === '/products') {
// Gestione della richiesta GET per ottenere tutti i prodotti
                handleGetProducts();
            } elseif (preg_match('/\/products\/(\d+)/', $path, $matches)) {
// Gestione della richiesta GET per ottenere un singolo prodotto
                $id = $matches[1];
                handleGetProduct($id);
            }
            break;
        case 'POST':
            if ($path === '/products') {
// Gestione della richiesta POST per creare un nuovo prodotto
                handleCreateProduct();
            }
            break;
        case 'PATCH':
            if (preg_match('/\/products\/(\d+)/', $path, $matches)) {
// Gestione della richiesta PATCH per aggiornare un prodotto
                $id = $matches[1];
                handleUpdateProduct($id);
            }
            break;
        case 'DELETE':
            if (preg_match('/\/products\/(\d+)/', $path, $matches)) {
// Gestione della richiesta DELETE per eliminare un prodotto
                $id = $matches[1];
                handleDeleteProduct($id);
            }
            break;
        default:
// Metodo HTTP non supportato
            http_response_code(405); // Method Not Allowed
            echo "405 Method Not Allowed";
            break;
    }
}

// Funzione per gestire la richiesta GET per ottenere tutti i prodotti
function handleGetProducts()
{
    try {
        $products = Product::FetchAll();
        $data = ['data' => []];
        foreach ($products as $product) {
            $data['data'][] = ['type' => 'products', 'id' => $product->getId(), 'attributes' => ['name' => $product->getNome(), 'price' => $product->getPrezzo(), 'marca' => $product->getMarca()]];
        }
        header('Content-Type: application/vnd.api+json');
        echo json_encode($data);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

// Funzione per gestire la richiesta GET per ottenere un singolo prodotto
function handleGetProduct($id)
{
    try {
        $product = Product::Find($id);
        $data = ['data' => ['type' => 'products', 'id' => $product->getId(), 'attributes' => ['name' => $product->getNome(), 'price' => $product->getPrezzo(), 'marca' => $product->getMarca()]]];
        header('Content-Type: application/vnd.api+json');
        echo json_encode($data);
    } catch (PDOException $e) {
        http_response_code(404);
        echo json_encode(['error' => 'Product not found']);
    }
}

// Funzione per gestire la richiesta POST per creare un nuovo prodotto
function handleCreateProduct()
{
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        $product = Product::Create($data['data']['attributes']);
        $data = ['data' => ['type' => 'products', 'id' => $product->getId(), 'attributes' => ['name' => $product->getNome(), 'price' => $product->getPrezzo(), 'marca' => $product->getMarca()]]];
        header('Content-Type: application/vnd.api+json');
        header('Location: http://example.com/products/' . $product->getId());
        http_response_code(201);
        echo json_encode($data);
    } catch (PDOException $e) {
        http_response_code(400);
        echo json_encode(['error' => 'Bad request: ' . $e->getMessage()]);
    }
}

// Funzione per gestire la richiesta PATCH per aggiornare un prodotto
function handleUpdateProduct($id)
{
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        $product = Product::Find($id);
        $product->setNome($data['data']['attributes']['name']);
        $product->setPrezzo($data['data']['attributes']['price']);
        $product->setMarca($data['data']['attributes']['marca']);
        $product->update($product->getNome(), $product->getMarca(), $product->getPrezzo());
        $data = ['data' => ['type' => 'products', 'id' => $product->getId(), 'attributes' => ['name' => $product->getNome(), 'price' => $product->getPrezzo(), 'marca' => $product->getMarca()]]];
        header('Content-Type: application/vnd.api+json');
        header('Location: http://example.com/products/' . $product->getId());
        echo json_encode($data);
    } catch (PDOException $e) {
        http_response_code(404);
        echo json_encode(['error' => 'Product not found']);
    }
}

// Funzione per gestire la richiesta DELETE per eliminare un prodotto
function handleDeleteProduct($id)
{
    try {
        $product = Product::Find($id);
        $product->delete();
        $data = ['meta' => ['message' => 'Product deleted successfully.']];
        header('Content-Type: application/vnd.api+json');
        echo json_encode($data);
    } catch (PDOException $e) {
        http_response_code(404);
        echo json_encode(['error' => 'Product not found']);
    }
}

handleRequest();
