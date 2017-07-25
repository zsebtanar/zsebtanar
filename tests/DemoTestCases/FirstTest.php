<?php
class ZsebTanarTest extends PHPUnit_Framework_TestCase {

    /**
     * @var \RemoteWebDriver
     */
    protected $webDriver;

	public function setUp()
    {
        $capabilities = array(\WebDriverCapabilityType::BROWSER_NAME => 'firefox');
        $this->webDriver = RemoteWebDriver::create('http://localhost:4444/wd/hub', $capabilities);
    }

    protected $url = 'http://localhost:1234/zsebtanar/';

    public function testZsebtanarHome()
    {
        $this->webDriver->get($this->url);
        // checking that page title contains word 'Zsebtanár'
        $this->assertContains('Zsebtanár - matek | másként', $this->webDriver->getTitle());
    }    

}
?>