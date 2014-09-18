awis
====

PHP package for making requests to Alexa Web Information Service

##Instalation

The easiest way to install Awis is via [composer](http://getcomposer.org/). Create the following `composer.json` file and run the `php composer.phar install` command to install it.

```json
{
    "require": {
        "nticaric/awis": "dev-master"
    }
}
```

##Examples

### UrlInfo

The `UrlInfo` action provides information about a website, such as:
* how popular the site is
* what sites are related
* contact information for the owner of the site

Usage:
```php

    use Nticaric\Awis\Awis;

    $awis = new Awis("ACCESS_KEY_ID", "SECRET_ACCESS_KEY");
    $response = $awis->getUrlInfo("example.com");

    //prints the raw xml response
    echo (string) $response->getBody();

```

The `getUrlInfo` method supports a second argument that lets you set a valid `ResponseGroup`.
The default is set to `ContentData`. Possible values for the response group are:

#### Response Groups

| Response Group  | Data Returned           |
| --------------- | ----------------------- |
| RelatedLinks    | Up to 11 related links  |
| Categories      | Up to 3 DMOZ (Open Directory) categories the site belongs to |
| Rank            | The Alexa three month average traffic rank |
| RankByCountry   | Percentage of viewers, page views, and traffic rank broken out by country |
| RankByCity      | Percentage of viewers, page views, and traffic rank broken out by city |
| UsageStats      | Usage statistics such as reach and page views |
| ContactInfo     | Contact information for the site owner or registrar |
| AdultContent    | Whether the site is likely to contain adult content ('yes' or 'no') |
| Speed           | Median load time and percent of known sites that are slower |
| Language        | Content language code and character-encoding (note that this may not match the language or character encoding of any given page on the website because the languange and character set returned are those of the majority of pages) |
| Keywords        | Keywords relevant to site content |
| OwnedDomains    | Other domains owned by the same owner as this site |
| LinksInCount    | A count of links pointing in to this site |
| SiteData        | Title, description, and date the site was created |

#### Meta-Response Groups

| Response Group  | Data Returned           |
| --------------- | ----------------------- |
| Related         | Up to 11 related links and up to 3 DMOZ categories (equivalent to ResponseGroup=RelatedLinks,Categories) |
| TrafficData     | Traffic rank and usage statistics (equivalent to ResponseGroup=Rank,UsageStats) |
| ContentData     | Information about the site's content (equivalent to ResponseGroup=SiteData,AdultContent,Popups,Speed,Language) |

Usage:
```php

    use Nticaric\Awis\Awis;

    $awis = new Awis("ACCESS_KEY_ID", "SECRET_ACCESS_KEY");
    $response = $awis->getUrlInfo("example.com", "ContentData");

```

### TrafficHistory

The TrafficHistory action returns the daily Alexa Traffic Rank, Reach per Million Users, and Unique Page Views per Million Users for each day since August 2007. This same data is used to produce the traffic graphs found on alexa.com.

Usage:
```php

    use Nticaric\Awis\Awis;

    $awis = new Awis("ACCESS_KEY_ID", "SECRET_ACCESS_KEY");
    $response = $awis->getTrafficHistory("example.com");

```

### CategoryBrowse

The `CategoryBrowse` action and `CategoryListings` actions together provide a directory service based on the Open Directory, www.dmoz.org, and enhanced with Alexa traffic data.

For any given category, the CategoryBrowse action returns a list of sub-categories. Within a particular category you can use the CategoryListings action to get the documents within that category ordered by traffic.

Usage:
```php

    use Nticaric\Awis\Awis;

    $awis = new Awis("ACCESS_KEY_ID", "SECRET_ACCESS_KEY");
    $response = $awis->getCategoryBrowse("example.com", "Categories", "Top/Arts");

```

### CategoryListings

The `CategoryListings` action is a directory service based on the Open Directory, www.dmoz.org. For any given category, it returns a list of site listings contained within that category.

Usage:
```php

    use Nticaric\Awis\Awis;

    $awis = new Awis("ACCESS_KEY_ID", "SECRET_ACCESS_KEY");
    $response = $awis->getCategoryListings("example.com", "Top/Arts", "Popularity", "False", 1, 20);

```

### SitesLinkingIn

The `SitesLinkingIn` action returns a list of web sites linking to a given web site. Within each domain linking into the web site, only a single link - the one with the highest page-level traffic - is returned. The data is updated once every two months.

Usage:
```php

    use Nticaric\Awis\Awis;

    $awis = new Awis("ACCESS_KEY_ID", "SECRET_ACCESS_KEY");
    $response = $this->awis->getSitesLinkingIn("example.com");

```

