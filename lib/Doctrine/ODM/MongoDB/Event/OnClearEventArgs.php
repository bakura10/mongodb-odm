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
 * and is licensed under the LGPL. For more information, see
 * <http://www.doctrine-project.org>.
 */

namespace Doctrine\ODM\MongoDB\Event;

/**
 * Provides event arguments for the onClear event.
 *
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.doctrine-project.org
 * @since       2.0
 * @author      Roman Borschel <roman@code-factory.de>
 * @author      Benjamin Eberlei <kontakt@beberlei.de>
 * @author      Jonathan H. Wage <jonwage@gmail.com>
 */
class OnClearEventArgs extends \Doctrine\Common\EventArgs
{
    /**
     * @var \Doctrine\ODM\MongoDB\DocumentManager
     */
    private $dm;

    /**
     * @var string
     */
    private $documentClass;

    /**
     * Constructor.
     *
     * @param \Doctrine\ODM\MongoDB\DocumentManager $dm
     * @param string $documentClass Optional document class
     */
    public function __construct($dm, $documentClass = null)
    {
        $this->em          = $dm;
        $this->documentClass = $documentClass;
    }

    /**
     * Retrieve associated DocumentManager.
     *
     * @return \Doctrine\ODM\MongoDB\DocumentManager
     */
    public function getDocumentManager()
    {
        return $this->em;
    }

    /**
     * Name of the document class that is cleared, or empty if all are cleared.
     *
     * @return string
     */
    public function getDocumentClass()
    {
        return $this->documentClass;
    }

    /**
     * Check if event clears all documents.
     *
     * @return bool
     */
    public function clearsAllDocuments()
    {
        return ($this->documentClass === null);
    }
}