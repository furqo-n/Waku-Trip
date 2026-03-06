# Lighthouse Analysis Raw Data

## Categories
- **Performance**: 46
- **Accessibility**: 91
- **Best Practices**: 96
- **SEO**: 75

## Issues by Category

### Performance
#### Audit: First Contentful Paint (first-contentful-paint)
- **Score**: 0.15 | **Weight**: 10
- **Value**: 2.4 s
- **Description**: First Contentful Paint marks the time at which the first text or image is painted. [Learn more about the First Contentful Paint metric](https://developer.chrome.com/docs/lighthouse/performance/first-contentful-paint/).

#### Audit: Largest Contentful Paint (largest-contentful-paint)
- **Score**: 0.37 | **Weight**: 25
- **Value**: 2.8 s
- **Description**: Largest Contentful Paint marks the time at which the largest text or image is painted. [Learn more about the Largest Contentful Paint metric](https://developer.chrome.com/docs/lighthouse/performance/lighthouse-largest-contentful-paint/)

#### Audit: Cumulative Layout Shift (cumulative-layout-shift)
- **Score**: 0.18 | **Weight**: 25
- **Value**: 0.472
- **Description**: Cumulative Layout Shift measures the movement of visible elements within the viewport. [Learn more about the Cumulative Layout Shift metric](https://web.dev/articles/cls).

#### Audit: Speed Index (speed-index)
- **Score**: 0.12 | **Weight**: 10
- **Value**: 3.8 s
- **Description**: Speed Index shows how quickly the contents of a page are visibly populated. [Learn more about the Speed Index metric](https://developer.chrome.com/docs/lighthouse/performance/speed-index/).


### Accessibility
#### Audit: Heading elements are not in a sequentially-descending order (heading-order)
- **Score**: 0 | **Weight**: 3
- **Description**: Properly ordered headings that do not skip levels convey the semantic structure of the page, making it easier to navigate and understand when using assistive technologies. [Learn more about heading order](https://dequeuniversity.com/rules/axe/4.11/heading-order).

#### Audit: Select elements do not have associated label elements. (select-name)
- **Score**: 0 | **Weight**: 10
- **Description**: Form elements without effective labels can create frustrating experiences for screen reader users. [Learn more about the `select` element](https://dequeuniversity.com/rules/axe/4.11/select-name).

#### Audit: Document does not have a main landmark. (landmark-one-main)
- **Score**: 0 | **Weight**: 3
- **Description**: One main landmark helps screen reader users navigate a web page. [Learn more about landmarks](https://dequeuniversity.com/rules/axe/4.11/landmark-one-main).


### Best Practices
#### Audit: Browser errors were logged to the console (errors-in-console)
- **Score**: 0 | **Weight**: 1
- **Description**: Errors logged to the console indicate unresolved problems. They can come from network request failures and other browser concerns. [Learn more about this errors in console diagnostic audit](https://developer.chrome.com/docs/lighthouse/best-practices/errors-in-console/)


### SEO
#### Audit: Document does not have a meta description (meta-description)
- **Score**: 0 | **Weight**: 1
- **Description**: Meta descriptions may be included in search results to concisely summarize page content. [Learn more about the meta description](https://developer.chrome.com/docs/lighthouse/seo/meta-description/).

#### Audit: Links are not crawlable (crawlable-anchors)
- **Score**: 0 | **Weight**: 1
- **Description**: Search engines may use `href` attributes on links to crawl websites. Ensure that the `href` attribute of anchor elements links to an appropriate destination, so more pages of the site can be discovered. [Learn how to make links crawlable](https://support.google.com/webmasters/answer/9112205)

#### Audit: robots.txt is not valid (robots-txt)
- **Score**: 0 | **Weight**: 1
- **Value**: 1 error found
- **Description**: If your robots.txt file is malformed, crawlers may not be able to understand how you want your website to be crawled or indexed. [Learn more about robots.txt](https://developer.chrome.com/docs/lighthouse/seo/invalid-robots-txt/).


## Key Metrics
- **First Contentful Paint**: 2.4 s (Score: 0.15)
- **Largest Contentful Paint**: 2.8 s (Score: 0.37)
- **Total Blocking Time**: 0 ms (Score: 1)
- **Cumulative Layout Shift**: 0.472 (Score: 0.18)
- **Speed Index**: 3.8 s (Score: 0.12)
- **Time to Interactive**: 2.8 s (Score: 0.83)
