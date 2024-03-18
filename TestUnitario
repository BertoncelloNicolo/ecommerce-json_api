<?php

require "Product.php";
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testTrovaProdottoEsistente()
    {
        $prodotto = Product::Find(1);

        $this->assertNotFalse($prodotto);
        $this->assertInstanceOf(Product::class, $prodotto);
        $this->assertEquals(1, $prodotto->getId());
    }

    public function testTrovaProdottoNonEsistente()
    {
        $prodotto = Product::Find(100);

        $this->assertFalse($prodotto);
    }

    public function testCreazioneProdotto()
    {
        $parametri = [
            "nome" => "Prodotto di Test",
            "marca" => "Marca di Test",
            "prezzo" => 10
        ];

        $prodotto = Product::Create($parametri);

        $this->assertNotFalse($prodotto);
        $this->assertInstanceOf(Product::class, $prodotto);
        $this->assertEquals("Prodotto di Test", $prodotto->getNome());
        $this->assertEquals("Marca di Test", $prodotto->getMarca());
        $this->assertEquals(10, $prodotto->getPrezzo());

        $prodotto->Delete();
    }

    public function testAggiornamentoProdotto()
    {
        $parametri = [
            "nome" => "Nuovo Nome",
            "marca" => "Nuova Marca",
            "prezzo" => 20
        ];

        $prodotto = Product::Find(1);

        $prodottoAggiornato = $prodotto->Update($parametri);

        $this->assertNotFalse($prodottoAggiornato);
        $this->assertInstanceOf(Product::class, $prodottoAggiornato);
        $this->assertEquals("Nuovo Nome", $prodottoAggiornato->getNome());
        $this->assertEquals("Nuova Marca", $prodottoAggiornato->getMarca());
        $this->assertEquals(20, $prodottoAggiornato->getPrezzo());

        $prodottoOriginale = Product::Find(1);
        $prodottoOriginale->Update([
            "nome" => "Nome originale",
            "marca" => "Marca originale",
            "prezzo" => 15
        ]);
    }

    public function testElencoProdotti()
    {
        $prodotti = Product::FetchAll();

        $this->assertNotEmpty($prodotti);
        foreach ($prodotti as $prodotto) {
            $this->assertInstanceOf(Product::class, $prodotto);
        }
    }
}
