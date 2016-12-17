<?php
namespace Application\View\Helper;
use Zend\View\Helper\AbstractHelper;
use Application\Renderer\ActiveRendererInterface as Renderer;
use Zend\Session\Container;
use Application\Active;
 
class Toolbar extends AbstractHelper
{
    /**
     */
    protected $active;

    /**
     * The name of the template used to render the calendar.
     *
     * @var null|string
     */
    protected $partial;

    protected $username;

    /**
     * Class to generate HTML version of the calendar.
     *
     * @var Renderer
     */
    protected $renderer;

    /**
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Sets the value of partial
     *
     * @param  string $partial
     * @return self
     */
    public function setPartial($partial)
    {
        $this->partial = (string) $partial;
        return $this;
    }

    /**
     * Gets the value of partial
     *
     * @return string
     */
    public function getPartial()
    {
        return $this->partial;
    }
    protected $context;

    public function setUsername($username)
    {
        $this->username = $username;
    }
    public function setContext($context)
    {
	$this->context = $context;
    }
    public function showOutput($attempt)
    {
	$context = $this->context;
        $renderer = $this->getRenderer();
	if ($context == "Issue")
	{
    		$retval = "<div id='toolbar'>";
		$retval .= "<ul class='itemlist'>";
		$retval .= "<li class='itemtab'><a href='/issue/index?new=issue'>New Issue</a></li>";
		$retval .= "</ul>";
		$retval .= "</div>";
	}
	else
	{
    		$retval = "<div id='toolbar'>";
		$retval .= "<ul class='itemlist'>";
		$retval .= "<li class='itemtab'><a href='/issue/correspondant?new=Wordage'>New Wordage</a></li>";
		$retval .= "</ul>";
		$retval .= "</div>";
	}

        return $retval;
    }

    /**
     * Set the renderer to be used.
     *
     * @param Renderer $renderer
     *
     * @return self
     * @todo Accept closure to generate renderer.
     */
    public function setRenderer(Renderer $renderer)
    {
        $this->renderer = $renderer;
        return $this;
    }

    /**
     * Gets the value of renderer
     *
     * @return Renderer
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    public function __invoke()
    {
    	$retval = "<div id='toolbar'>";
	$retval .= "<ul class='itemlist'>";
	$retval .= "<li class='itemtab_light'><a href='/correspondant/index?new=wordage'>Wordage</a></li>";
	$retval .= "<li class='itemtab_light'>&nbsp;&nbsp;&nbsp;</li>";
	$retval .= "<li class='itemtab_light'><a href='http://dev.newhollandpress.com/article/new'>Free-form Article</a></li>";
	$retval .= "<li class='itemtab_light'>&nbsp;&nbsp;&nbsp;</li>";
	$retval .= "<li class='itemtab_light'><a href='http://dev.newhollandpress.com/pix/new'>Pix</a></li>";
	$retval .= "<li class='itemtab_light'>&nbsp;&nbsp;&nbsp;</li>";
	$retval .= "<li class='itemtab_light'><a href='http://dev.newhollandpress.com/container/new'>Container</a></li>";
	$retval .= "</ul>";
	$retval .= "</div>";
        return $retval;
    }
}
?>
