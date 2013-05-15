<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license. For more information, see
 * <http://www.doctrine-project.org>.
 */

namespace Doctrine\ODM\MongoDB\Criteria;

use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\Common\Collections\Expr\CompositeExpression;
use Doctrine\Common\Collections\Expr\ExpressionVisitor;
use Doctrine\Common\Collections\Expr\Value;
use Doctrine\MongoDB\Query\Builder;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadataFactory;
use Doctrine\ODM\MongoDB\Query\Expr;

/**
 * @since       1.0
 * @author      Antoine Hedgecock <antoine@pmg.se>
 * @author      Michaël Gallego <mic.gallego@gmail.com>
 */
class MongoExpressionVisitor extends ExpressionVisitor
{
    /**
     * Map Criteria API expressions to MongoDB ones
     *
     * @var array
     */
    protected $comparisonTable = array(
        '='   => '',
        '<>'  => 'ne',
        '<'   => 'lt',
        '<='  => 'lte',
        '>'   => 'gt',
        '>='  => 'gte',
        'IN'  => 'in',
        'NIN' => 'nin'
    );

    /**
     * @var Builder
     */
    protected $queryBuilder;

    /**
     * @var ClassMetadata
     */
    protected $metadata;

    /**
     * @var ClassMetadataFactory
     */
    protected $metadataFactory;

    /**
     * @var Expr
     */
    protected $expr;

    /**
     * @param Builder $queryBuilder
     * @param ClassMetadata $metadata
     * @param ClassMetadataFactory $metadataFactory
     */
    public function __construct(Builder $queryBuilder, ClassMetadata $metadata, ClassMetadataFactory $metadataFactory)
    {
        $this->queryBuilder    = $queryBuilder;
        $this->metadata        = $metadata;
        $this->metadataFactory = $metadataFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function walkComparison(Comparison $comparison)
    {
        $operator = $comparison->getOperator();
        $field    = $comparison->getField();
        $value    = $this->dispatch($comparison->getValue());

        if (isset($this->comparisonTable[$operator])) {
            if ($operator === Comparison::EQ) {
                return $this->getExpr()->field($field)
                                       ->equals($value);
            } else {
                return $this->getExpr()->field($field)
                                       ->operator($this->comparisonTable[$operator], $value);
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function walkValue(Value $value)
    {
        return $value;
    }

    /**
     * {@inheritDoc}
     */
    public function walkCompositeExpression(CompositeExpression $expr)
    {
        $children = array();

        foreach ($expr->getExpressionList() as $child) {
            $children[] = $this->dispatch($child);
        }

        switch ($expr->getType())
        {
            case CompositeExpression::TYPE_AND:
                return $this->getExpr()->addAnd($children);

            case CompositeExpression::TYPE_OR:
                return $this->getExpr()->addOr($children);
        }
    }

    /**
     * @return Expr
     */
    private function getExpr()
    {
        if (null === $this->expr) {
            $this->expr = $this->queryBuilder->expr();
        }

        return $this->expr;
    }
}
