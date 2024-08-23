<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 16.11.14
 * Time: 17:43
 */

namespace Mtt\BlogBundle\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\Pagination\SlidingPagination;
use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\PaginatorInterface;
use Mtt\BlogBundle\API\DataConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BaseController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var PaginatorInterface
     */
    protected $paginator;

    /**
     * @var DataConverter
     */
    protected $apiDataConverter;

    protected array $errorsPathMap = [];

    /**
     * @param EntityManagerInterface $em
     * @param PaginatorInterface $paginator
     * @param DataConverter $apiDataConverter
     */
    public function __construct(
        EntityManagerInterface $em,
        PaginatorInterface $paginator,
        DataConverter $apiDataConverter
    ) {
        $this->em = $em;
        $this->paginator = $paginator;
        $this->apiDataConverter = $apiDataConverter;
    }

    /**
     * @return EntityManager
     */
    public function getEm(): EntityManagerInterface
    {
        return $this->em;
    }

    /**
     * @return DataConverter
     */
    public function getDataConverter(): DataConverter
    {
        return $this->apiDataConverter;
    }

    /**
     * @return Paginator
     */
    public function getPaginator(): PaginatorInterface
    {
        return $this->paginator;
    }

    /**
     * @param $query
     * @param $page
     * @param int $limit
     *
     * @return SlidingPagination
     */
    public function paginate($query, $page, int $limit = 15): PaginationInterface
    {
        return $this->getPaginator()
            ->paginate($query, (int)$page, $limit);
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function getPaginationMetadata(array $data): array
    {
        return [
            'last' => $data['last'],
            'current' => $data['current'],
            'previous' => $data['previous'] ?? false,
            'next' => $data['next'] ?? false,
        ];
    }

    /**
     * @param FormInterface $form
     *
     * @return array
     */
    protected function handleForm(FormInterface $form): array
    {
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $formData = $form->getData();
            } else {
                $errors = ['errors' => []];
                /* @var \Symfony\Component\Form\FormError $formError */
                foreach ($form->getErrors(true) as $formError) {
                    $errors['errors'][] = [
                        'message' => $formError->getMessage(),
                        'path' => $this->fixErrorPath($formError->getCause()->getPropertyPath()),
                    ];
                }

                return [null, $errors];
            }
        } else {
            throw new BadRequestHttpException('Form not submitted');
        }

        return [$formData, null];
    }

    /**
     * @param $child
     * @param $type
     * @param bool $put
     *
     * @return FormInterface
     */
    protected function createObjectForm($child, $type, bool $put = false): FormInterface
    {
        $fb = $this->container
            ->get('form.factory')
            ->createNamedBuilder('', FormType::class, null, [
                'csrf_protection' => false,
                'method' => $put ? 'PUT' : 'POST',
            ]);

        $fb->add($child, $type);

        return $fb->getForm();
    }

    protected function validate(ValidatorInterface $validator, object $entity): array
    {
        $errors = [];
        $violations = $validator->validate($entity);
        if (count($violations) > 0) {
            $errors = ['errors' => []];
            /* @var ConstraintViolationInterface $violation */
            foreach ($violations as $violation) {
                $errors['errors'][] = [
                    'message' => $violation->getMessage(),
                    'path' => $this->fixErrorPath($violation->getPropertyPath()),
                ];
            }
        }

        return $errors;
    }

    private function fixErrorPath(string $path): string
    {
        return $this->errorsPathMap[$path] ?? 'unknown';
    }
}
