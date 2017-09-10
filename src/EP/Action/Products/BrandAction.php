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
        /** @var BrandRepository $brandRepo */
        $brandRepo = Kernel::em()->getRepository(Brand::class);

        if (Kernel::uri()->getMethod() == 'POST') {
            $brandsPost = filter_input(INPUT_POST, 'name', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

            if (!empty($brandsPost)) {
                foreach ($brandsPost as $id => $brand) {
                    if (empty($brand)) {
                        continue;
                    }

                    // É cadstro?
                    if ($id == 'new_brand') {
                        $thisBrand = new Brand();
                        $thisBrand->setName($brand);
                        if (!$brandRepo->create($thisBrand)) {
                            $erro = true;
                        }
                    } else {
                        /** @var Brand $thisBrand */
                        $thisBrand = $brandRepo->find($id);
                        $thisBrand->setName($brand);
                        if (!$brandRepo->update($thisBrand)) {
                            $erro = true;
                        }
                    }

                    $msg = $erro ? 'Não foi possível atualizar todas as marcas' : 'Marcas atualizadas com sucesso';
                }
            }
        }

        $brands = $brandRepo->findAll('name');

        echo Kernel::template()->render('products/brands.twig', [
            'msg'    => $msg,
            'erro'   => $erro,
            'brands' => $brands,
        ]);
    }
}