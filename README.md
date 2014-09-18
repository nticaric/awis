awis
====

PHP package for making requests to Alexa Web Information Service

##Examples

The UrlInfo action provides information about a website, such as:

* how popular the site is
* what sites are related
* contact information for the owner of the site
    
    use Nticaric\Awis\Awis;

    $awis = new Awis("ACCESS_KEY_ID", "SECRET_ACCESS_KEY");
    $response = $awis->getUrlInfo("kickstarter.com");

The `getUrlInfo` method supports a second argument that lets you set a valid `ResponseGroup`.
The default is set to `ContentData`. Possible values for the response group are:

###Response Groups

RelatedLinks | Up to 11 related links
Categories | Up to 3 DMOZ (Open Directory) categories the site belongs to
Rank | The Alexa three month average traffic rank
RankByCountry | Percentage of viewers, page views, and traffic rank broken out by country
RankByCity | Percentage of viewers, page views, and traffic rank broken out by city
UsageStats | Usage statistics such as reach and page views
ContactInfo | Contact information for the site owner or registrar
AdultContent | Whether the site is likely to contain adult content ('yes' or 'no')
Speed | Median load time and percent of known sites that are slower
Language | Content language code and character-encoding (note that this may not match the language or character encoding of any given page on the website because the languange and character set returned are those of the majority of pages)
Keywords | Keywords relevant to site content
OwnedDomains | Other domains owned by the same owner as this site
LinksInCount | A count of links pointing in to this site
SiteData | Title, description, and date the site was created

### Meta-Response Groups

Related | Up to 11 related links and up to 3 DMOZ categories (equivalent to ResponseGroup=RelatedLinks,Categories)
TrafficData | Traffic rank and usage statistics (equivalent to ResponseGroup=Rank,UsageStats)
ContentData | Information about the site's content (equivalent to ResponseGroup=SiteData,AdultContent,Popups,Speed,Language)