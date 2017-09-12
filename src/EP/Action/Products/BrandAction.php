<?php
/**
 * Ação para Lista de Marcas
 *
 * @author Daniel Bispo <szagot@gmail.com.br>
 * @since  10/09/2017
 */


namespace EP\Action\Products;

use EP\App\Kernel;
use EP\Action\ActionInterface;
use EP\Entities\Products\Brand;
use EP\Repositories\Products\BrandRepository;

class BrandAction implements ActionInterface
{

    /**
     * Executa a página atual
     *
     * @param string $method    Método da requisição
     * @param string $type      Tipo de saída
     * @param array  $uriParams Parâmetros da requisição enviados pela URI
     */
    public static function run($method, $type, $uriParams = [])
    {
        $msg = null;
        $erro = false;
        /** @var BrandRepository $repo */
        $repo = Kernel::em()->getRepository(Brand::class);

        if (Kernel::uri()->getMethod() == 'POST') {
            $postName = filter_input(INPUT_POST, 'name', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

            if (!empty($postName)) {
                foreach ($postName as $id => $brand) {
                    if (empty($brand)) {
                        continue;
                    }

                    // É cadastro?
                    if ($id == 'new_brand') {
                        $thisBrand = new Brand();
                        $thisBrand->setName($brand);
                        if (!$repo->create($thisBrand)) {
                            $erro = true;
                        }
                    } else {
                        /** @var Brand $thisBrand */
                        $thisBrand = $repo->find($id);
                        $thisBrand->setName($brand);
                        if (!$repo->update($thisBrand)) {
                            $erro = true;
                        }
                    }
                }

                $msg = $erro ? 'Não foi possível atualizar todas as marcas' : 'Marcas atualizadas com sucesso';
            }
        }

        $brands = $repo->findAll('name');

        echo Kernel::template()->render('products/brands.twig', [
            'msg'    => $msg,
            'erro'   => $erro,
            'brands' => $brands,
        ]);
    }
}