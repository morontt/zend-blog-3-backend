<?php

/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 16.11.14
 * Time: 17:43
 */

namespace App\Controller;

use App\API\DataConverter;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BaseController extends AbstractController
{
    /**
     * @var array<string, string>
     */
    protected array $errorsPathMap = [];

    public function __construct(
        private EntityManagerInterface $em,
        private PaginatorInterface $paginator,
        private DataConverter $apiDataConverter,
    ) {
    }

    protected function getEm(): EntityManagerInterface
    {
        return $this->em;
    }

    protected function getDataConverter(): DataConverter
    {
        return $this->apiDataConverter;
    }

    /**
     * @param Query<null, mixed> $query
     *
     * @phpstan-ignore missingType.iterableValue
     */
    protected function paginate(Query $query, int $page, int $limit = 15): PaginationInterface
    {
        return $this->paginator->paginate($query, (int)$page, $limit);
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    protected function getPaginationMetadata(array $data): array
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
     * @return mixed[]
     */
    protected function handleForm(FormInterface $form): array
    {
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $formData = $form->getData();
            } else {
                $errors = ['errors' => []];
                /** @var \Symfony\Component\Form\FormError $formError */
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
     * @param string|\Symfony\Component\Form\FormBuilderInterface $child
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     *
     * @return FormInterface
     */
    protected function createObjectForm($child, ?string $type, bool $put = false): FormInterface
    {
        /** @var \Symfony\Component\Form\FormFactory $formFactory */
        $formFactory = $this->container
            ->get('form.factory');

        $formBuilder = $formFactory
            ->createNamedBuilder('', FormType::class, null, [
                'csrf_protection' => false,
                'method' => $put ? 'PUT' : 'POST',
            ]);

        return $formBuilder->add($child, $type)->getForm();
    }

    /**
     * @return array<string, array<int, array <string, string>>>
     */
    protected function validate(ValidatorInterface $validator, object $entity): array
    {
        $errors = [];
        $violations = $validator->validate($entity);
        if (count($violations) > 0) {
            $errors = ['errors' => []];
            /** @var \Symfony\Component\Validator\ConstraintViolationInterface $violation */
            foreach ($violations as $violation) {
                $errors['errors'][] = [
                    'message' => $violation->getMessage(),
                    'path' => $this->fixErrorPath($violation->getPropertyPath()),
                ];
            }
        }

        return $errors;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getArrayData(Request $request, string $key): array
    {
        $requestData = null;
        if ($request->request->has($key)) {
            $requestData = $request->request->all()[$key];
        }

        if (!is_array($requestData)) {
            throw new BadRequestHttpException("Empty '{$key}' data");
        }

        return $requestData;
    }

    private function fixErrorPath(string $path): string
    {
        return $this->errorsPathMap[$path] ?? 'unknown';
    }
}
