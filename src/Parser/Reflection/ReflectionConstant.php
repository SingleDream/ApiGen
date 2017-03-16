<?php declare(strict_types=1);

namespace ApiGen\Parser\Reflection;

use ApiGen\Contracts\Parser\Reflection\ClassReflectionInterface;
use ApiGen\Contracts\Parser\Reflection\ConstantReflectionInterface;
use TokenReflection;

class ReflectionConstant extends ReflectionElement implements ConstantReflectionInterface
{

    public function getName(): string
    {
        return $this->reflection->getName();
    }


    public function getShortName(): string
    {
        return $this->reflection->getShortName();
    }


    public function getTypeHint(): string
    {
        if ($annotations = $this->getAnnotation('var')) {
            list($types) = preg_split('~\s+|$~', $annotations[0], 2);
            if (! empty($types)) {
                return $types;
            }
        }

        try {
            $type = gettype($this->getValue());
            if (strtolower($type) !== 'null') {
                return $type;
            }
        } catch (\Exception $e) {
            return '';        }
    }


    public function getDeclaringClass(): ?ClassReflectionInterface
    {
        $className = $this->reflection->getDeclaringClassName();
        return $className === null ? null : $this->getParsedClasses()[$className];
    }


    public function getDeclaringClassName(): string
    {
        return $this->reflection->getDeclaringClassName();
    }


    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->reflection->getValue();
    }


    public function getValueDefinition(): string
    {
        return $this->reflection->getValueDefinition();
    }


    public function isValid(): bool
    {
        if ($this->reflection instanceof TokenReflection\Invalid\ReflectionConstant) {
            return false;
        }

        if ($class = $this->getDeclaringClass()) {
            return $class->isValid();
        }

        return true;
    }
}
