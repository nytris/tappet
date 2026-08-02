# Tappet - Enjoyable GUI testing

[![Build Status](https://github.com/nytris/tappet/workflows/CI/badge.svg)](https://github.com/nytris/tappet/actions?query=workflow%3ACI)

> **[EXPERIMENTAL]** - API is unstable and subject to change.

Tappet is a GUI testing framework that lets you write end-to-end tests at a higher level of abstraction than traditional tools encourage.
Instead of hunting for CSS selectors or XPath queries, you describe *what* you want to interact with, and Tappet finds it.

## The problem with selector-based testing

A typical Cypress test might look like this:

```javascript
cy.get('#save-button').click();
cy.get('.flash-message').should('contain', 'User saved successfully');
```

This approach is fragile: the test is coupled to the IDs, classes, and text content of your HTML.
Rename `#save-button` to `#submit-btn`, restructure so that `.flash-message` is applied to a different element,
or change the wording of your flash message, and the test breaks, even though the application behaves correctly.

## Tappet's approach

Tappet replaces selector-based targeting with named *handles* wired to DOM elements via `data-ui-*` attributes:

```html
<!-- In your application HTML: -->
<button type="submit" data-ui-interaction="save">Save</button>

<div class="alert alert-success" data-ui-region="flash">
    User saved successfully
</div>
```

```php
// In your Tappet spec:
->act(
    new Enact('save')
)
->assert(
    new ExpectRegionContains('flash', 'User saved successfully')
)
```

Now you can rename the button's ID, change its CSS classes, or even swap it for an `<input type="submit">` -
none of that affects the test.
The `data-ui-interaction="save"` attribute is the stable contract between the application and its tests.

## Core abstractions

Tappet organises its abstractions into four categories, grouped under **Controls** and **Feedback**:

| Direction | Type                  | HTML attribute        | Purpose                                                                                      |
|-----------|-----------------------|-----------------------|----------------------------------------------------------------------------------------------|
| Control   | **Field Actions**     | `data-ui-field`       | Type into inputs, select options, and interact with form fields by handle                    |
| Feedback  | **Field Assertions**  | `data-ui-field`       | Assert a field's value, e.g. the content of a text input                                     |
| Control   | **Interactions**      | `data-ui-interaction` | Click buttons and follow links without caring about their selector or position               |
| Feedback  | **Region Assertions** | `data-ui-region`      | Assert the display, e.g. text content of named page areas such as headings or flash messages |
| Feedback  | **State Assertions**  | `data-ui-state`       | Assert application state by presence of a marker element, decoupled from copy                |

## Specs in PHP

Test specs are written in PHP using a fluent BDD-style arrange/act/assert API:

```php
<?php

declare(strict_types=1);

use Tappet\Runner\Standard\Action\Enact;
use Tappet\Runner\Standard\Action\Select;
use Tappet\Runner\Standard\Action\Type;
use Tappet\Runner\Standard\Arrangement\LoadFixture;
use Tappet\Runner\Standard\Arrangement\OpenPage;
use Tappet\Runner\Standard\Assertion\ExpectNewPage;
use Tappet\Runner\Standard\Assertion\ExpectRegionContains;
use Tappet\Runner\Tappet;

Tappet::describe('User Management -> User', [
    Tappet::it('first name can be changed')
        ->arrange(
            new LoadFixture('john-user', new UserFixture('John', 'Doe', 'john@example.com')),
            new OpenPage(new UserEditPage('john-user'))
        )
        ->act(
            new Type('first-name', 'Fred'),
            new Select('role', 'editor'),
            new Enact('save')
        )
        ->assert(
            new ExpectNewPage(new UserListPage()),
            new ExpectRegionContains('flash', 'User saved successfully')
        ),
]);
```

Note that fields and interactions are identified by handles - in the above example, `first-name`, `role`, `save` and `flash`,
which are wired to DOM elements via `data-ui-*` attributes.

Fixtures also use handles to identify the models they create, so that they can be referred back to later -
note how `john-user` is used to refer to the user created by the `LoadFixture` arrangement when navigating to their edit page.

## Installation

### Prerequisites

- PHP 8.1 or later
- [Composer](https://getcomposer.org/)

### 1. Install the core package

```bash
composer require --dev tappet/tappet
```

### 2. Install and configure an adapter

Tappet requires an adapter to run tests. Currently the [Cypress adapter][]
is the only available option - see its README for full installation and configuration instructions.

### 3. Create the CLI config

Create `tappet.config.php` in the root of your project:

```php
<?php

declare(strict_types=1);

use Tappet\Cli\Config\Config;

return new Config();
```

So that you can run `vendor/bin/tappet` without arguments, define some defaults that make sense
for your local development environment:

```php
<?php

declare(strict_types=1);

use Tappet\Cli\Config\Config;

$config = new Config();

// Define defaults to use when not specified on the command line:

// Suite name to run.
$config->setDefaultSuite('e2e');
// Base URL of the application under test.
$config->setDefaultBaseUrl('http://localhost:3000');
// Base URL of the Tappet fixture API (usually the same as the application base URL).
$config->setDefaultApiBaseUrl('http://localhost:3000');
// Shared secret used to authorise fixture API calls.
$config->setDefaultApiKey('your-api-key');
// Allow self-signed certs for local dev etc.
$config->setDefaultApiTlsVerification(false);

return $config;
```

### 4. Annotate your HTML

Add `data-ui-*` attributes to the relevant elements in your application:

```html
<form method="POST" action="/login">
    <input type="email" name="email" data-ui-field="email">
    <input type="password" name="password" data-ui-field="password">
    <button type="submit" data-ui-interaction="login">Log in</button>
</form>
```

After a successful login, render a hidden marker element or add the attribute to an existing visible element:

```html
<nav data-ui-state="logged-in">
    Welcome, {{ user.name }}
</nav>
```
To assert that the above state is reached on the loaded page, use `->assert(new ExpectState('logged-in'))`.

### 5. Implement the fixture API

Tappet loads and tears down test data via a small HTTP API in your application. Expose these endpoints:

| Method   | Path                                  | Purpose                                      |
|----------|---------------------------------------|----------------------------------------------|
| `POST`   | `/.well-known/tappet/fixture/{class}` | Create a single fixture                      |
| `POST`   | `/.well-known/tappet/fixtures`        | Create multiple fixtures (bulk)              |
| `DELETE` | `/.well-known/tappet/fixtures`        | Delete all fixtures created in this scenario |

All requests include an `Authorization: Bearer <key>` header, where `<key>` is the `tappetApiKey` configured in `tappet.config.php`.

For **Symfony** applications, [Tappet Bundle][] exposes these endpoints automatically -
install it instead of implementing them by hand. For other frameworks, implement the endpoints manually.

### 6. Run the suite

```bash
vendor/bin/tappet run e2e
```

Or, if you configured a default suite in `tappet.config.php`:

```bash
vendor/bin/tappet run
```

Or even just:

```bash
vendor/bin/tappet
```

## Writing specs

### Spec file structure

Each spec file calls `Tappet::describe()`. No class is required:

```php
<?php

declare(strict_types=1);

use Tappet\Runner\Standard\Action\Enact;
use Tappet\Runner\Standard\Action\Type;
use Tappet\Runner\Standard\Arrangement\LoadFixture;
use Tappet\Runner\Standard\Arrangement\OpenPage;
use Tappet\Runner\Standard\Assertion\ExpectNewPage;
use Tappet\Runner\Standard\Assertion\ExpectRegionContains;
use Tappet\Runner\Tappet;

Tappet::describe('User Management -> User', [
    Tappet::it('first name can be changed')
        ->arrange(
            new LoadFixture('john-user', new UserFixture('John', 'Doe', 'john@example.com')),
            new OpenPage(new UserEditPage('john-user'))
        )
        ->act(
            new Type('first-name', 'Fred'),
            new Enact('save')
        )
        ->assert(
            new ExpectNewPage(new UserListPage()),
            new ExpectRegionContains('flash', 'User saved successfully.')
        ),
]);
```

### Arrange stage

| Class                                               | Purpose                                                                         |
|-----------------------------------------------------|---------------------------------------------------------------------------------|
| `LoadFixture($handle, $fixture)`                    | Create a single piece of test data and store the resulting model under a handle |
| `LoadMultipleFixtures(['handle' => $fixture, ...])` | Create several fixtures in a single API call                                    |
| `OpenPage($page)`                                   | Navigate the browser to the URL built by a `PageInterface` implementation       |
| Custom `ArrangementInterface`                       | Application-specific setup (e.g. programmatic login)                            |

### Act stage

| Class                                               | Purpose                                                                         |
|-----------------------------------------------------|---------------------------------------------------------------------------------|
| `Check($handle)`                                    | Check a checkbox field                                                          |
| `ChooseRadioOption($handle, $value)`                | Select a radio button within a radio group by value                             |
| `Clear($handle)`                                    | Clear a text field without typing anything new                                  |
| `DoubleClick($handle)`                              | Double-click the element identified by the handle                               |
| `Enact($handle)`                                    | Click the element identified by the handle                                      |
| `Hover($handle)`                                    | Hover over the element (fires `mouseover`) to reveal tooltips or dropdowns      |
| `Select($handle, $value)`                           | Select an option of a `<select>` dropdown by value                              |
| `Type($handle, $text)`                              | Type text into a field, clearing any existing value first                       |
| `Uncheck($handle)`                                  | Uncheck a checkbox field                                                        |
| `Upload($handle, $filePath)`                        | Upload a file via a file input field                                            |
| `Visit($page)`                                      | Navigate to the given page during the act stage, updating the expected current page |
| Custom `ActionInterface`                            | Application-specific actions                                                    |

### Assert stage

| Class                                               | Purpose                                                                          |
|-----------------------------------------------------|----------------------------------------------------------------------------------|
| `ExpectFieldValue($handle, $value)`                 | Assert a field currently holds the given value                                   |
| `ExpectList($handle, $items)`                       | Assert a list region contains given items in order (see below)                   |
| `ExpectNewPage($page)`                              | Assert the browser has navigated to the URL built by the given page              |
| `ExpectRegionContains($handle, $text)`              | Assert the named region's text includes the given string                         |
| `ExpectRegionDoesNotContain($handle, $text)`        | Assert the named region's text does not include the given string                 |
| `ExpectState($handle)`                              | Assert a named state element is present in the DOM                               |
| `ExpectTable($handle, $rows)`                       | Assert a table region contains given rows (see below)                            |
| Custom `AssertionInterface`                         | Application-specific assertions                                                  |

### Using a step in an unusual stage

Each stage's `->arrange()`, `->act()` and `->assert()` methods only accept the matching interface -
`ArrangementInterface`, `ActionInterface` and `AssertionInterface` respectively. Occasionally it's useful to
perform a step in a different stage than usual, for example checking a field's value partway through the act
stage, right after the interaction that's expected to change it, rather than waiting until the assert stage.

Rather than having every step class implement all three interfaces (which would make it easy to place a step
in the wrong stage by accident), Tappet provides explicit wrapper classes for each combination. Wrapping a step
makes it obvious, when reading a spec, that a step is being used somewhere it wouldn't normally belong:

| Wrapping...                  | ...for use in the   | Class                                |
|------------------------------|---------------------|--------------------------------------|
| an `ArrangementInterface`    | act stage           | `ArrangementAction($arrangement)`    |
| an `ArrangementInterface`    | assert stage        | `ArrangementAssertion($arrangement)` |
| an `ActionInterface`         | arrange stage       | `ActionArrangement($action)`         |
| an `ActionInterface`         | assert stage        | `ActionAssertion($action)`           |
| an `AssertionInterface`      | arrange stage       | `AssertionArrangement($assertion)`   |
| an `AssertionInterface`      | act stage           | `AssertionAction($assertion)`        |

```php
->act(
    new Type('first-name', 'Fred'),
    new AssertionAction(new ExpectTextFieldValue('first-name', 'Fred')),
    new Enact('save')
)
```

A step that naturally belongs in more than one stage - for example `ExpectNewPage`, which can be asserted as a
precondition in the arrange stage just as sensibly as it can verify the outcome of the assert stage - may
implement the corresponding interfaces directly instead, without needing to be wrapped for those stages.
`ExpectNewPage` therefore implements both `ArrangementInterface` and `AssertionInterface`, but to check it
mid-act it must still be wrapped explicitly in `AssertionAction`, since that placement is the unusual one.

#### `ExpectTable` usage

`ExpectTable` takes a list of rows. Each row is an associative array mapping column handles
(matched by `data-ui-column` attributes on `<td>` or `<th>` cells) to a matcher against which the cell must match.
Annotate your table heading cells with `data-ui-column="<column-handle>"` to define the column handles:

```html
<table data-ui-region="users">
    <thead>
        <tr>
            <th data-ui-column="name">Name</th>
            <th data-ui-column="email">Email</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Alice</td>
            <td>alice@example.com</td>
        </tr>
    </tbody>
</table>
```

```php
<?php

use Tappet\Runner\Standard\Assertion\ExpectTable;
use Tappet\Runner\Standard\Matcher\ExactText;

// ...

new ExpectTable('users', [
    ['name' => new ExactText('Alice'), 'email' => new ExactText('alice@example.com')],
])
```

#### `ExpectList` usage

`ExpectList` takes an ordered list of item matchers, each matched to the corresponding
`<li>` element:

```html
<ul data-ui-region="recent-items">
    <li>First item</li>
    <li>Second item</li>
</ul>
```

```php
<?php

use Tappet\Runner\Standard\Assertion\ExpectList;
use Tappet\Runner\Standard\Matcher\ExactText;

// ...

new ExpectList('recent-items', [new ExactText('First item'), new ExactText('Second item')])
```

## Transitions

Some interactions don't just change what's on the page, they change *which* page (or page-like state) you're on:
navigating to a new URL, or a modal opening and closing on top of the current page. Tappet calls these events
**transitions**, and the test runner keeps an ordered log of every transition detected during a scenario.

Assertions and arrangements that expect a transition (e.g. `ExpectNewPage`) consume the next entry from this log,
waiting briefly if it hasn't happened yet, and fail immediately if a transition occurs that wasn't expected, or
if the wrong one occurs. At the end of a scenario, Tappet also asserts that the log is empty, so an
unexpected navigation or modal opening or closing that nothing asserted against will fail the test,
even if every explicit assertion in the spec passed.

### The built-in transition: page navigation

Navigating the browser - via `OpenPage` in the arrange stage, `Visit` in the act stage, or simply as the
side effect of an interaction such as submitting a form - logs a navigation transition automatically once the
new page finishes loading. For those not implicitly declared by use of `OpenPage` or `Visit`,
use `ExpectNewPage($page)` to consume it:

```php
->act(
    new Enact('save')
)
->assert(
    // Asserts that the "save" interaction above resulted in navigation to this page.
    new ExpectNewPage(new UserListPage())
)
```

`ExpectNewPage` implements `ArrangementInterface` as well as `AssertionInterface`, so it can be used
in whichever stage of the scenario makes sense. In the act stage, for clarity it must be wrapped
in `AssertionAction`, i.e. `new AssertionAction(new ExpectNewPage(...))`,
to make it obvious that it isn't itself an action despite being part of the act stage.

### Custom transitions

Not every meaningful page-state change is a full navigation. A modal opening or closing is a common example:
the URL doesn't change, but the user-visible "page" effectively does, and tests should be able to assert on it
similarly to a page navigation - including failing if a modal is left open (or closed) unexpectedly.

Adapter packages (and applications built on them) can define their own transitions by implementing
`Tappet\Runner\Transition\TransitionInterface`, which just needs to say whether it `equals()` another
transition and provide a human-readable `getDescription()` for failure messages. `tappet/cypress` uses this
to support custom modal transitions in its own test suite:

```php
class ModalOpenTransition implements TransitionInterface
{
    public function __construct(private readonly string $handle) {}

    public function equals(TransitionInterface $other): bool
    {
        return $other instanceof ModalOpenTransition && $other->handle === $this->handle;
    }

    public function getDescription(): string
    {
        return 'modal "' . $this->handle . '" opening';
    }
}
```

Pairing a custom transition with an `ArrangementInterface`/`AssertionInterface` implementation gives specs a
matching assertion class, `ExpectModalOpen`/`ExpectModalClosed` in this example, that simply calls
`$environment->assertTransition(...)` with the transition to expect:

```php
class ExpectModalOpen implements ArrangementInterface, AssertionInterface
{
    public function __construct(private readonly string $handle) {}

    public function perform(EnvironmentInterface $environment): void
    {
        $environment->assertTransition(new ModalOpenTransition($this->handle));
    }
}
```

Specs then use it like any other assertion, including via `AssertionAction` to assert mid-act, immediately
after the interaction that's expected to trigger it:

```php
->act(
    new Enact('open-add-user-modal'),
    new AssertionAction(new ExpectModalOpen('add-user')),
    new Enact('close-add-user-modal')
)
->assert(
    new ExpectModalClosed('add-user')
)
```

Defining the transition and its assertion classes is only half the story - something also needs to *detect*
the modal opening or closing and push the corresponding transition onto the log. That detection is necessarily
adapter-specific: it depends on hooks into the underlying test tool that only that adapter exposes. For
`tappet/cypress`, one approach is to register a plugin that listens for its `WindowBeforeLoadEvent` to attach a
`MutationObserver` in the application-under-test's window, then calls `CypressAutomationInterface::pushTransition()`
whenever a `data-ui-modal` element changes visibility. See the
[Cypress adapter's documentation](https://github.com/nytris/tappet-cypress) for the full details of how
custom transition detection is wired up there.

## Fixtures

Fixture classes extend `AbstractFixture` and declare which model class they produce:

```php
/**
 * @extends AbstractFixture<UserModel>
 */
class UserFixture extends AbstractFixture
{
    public function __construct(
        private string $firstName,
        private string $lastName,
        private string $email
    ) {}

    public static function getModelClass(): string
    {
        return UserModel::class;
    }

    // Getters (or use public properties)...
}
```

Model classes hold only the data needed to reference a created record, typically just an ID or reference:

```php
class UserModel implements ModelInterface
{
    public function __construct(private int $id) {}

    public function getId(): int
    {
        return $this->id;
    }
}
```

Fixture models are deleted automatically at the beginning of the next scenario, regardless of whether it passed or failed.

## Pages

Page classes implement `PageInterface` and encapsulate URL construction:

```php
class UserEditPage implements PageInterface
{
    public function __construct(private string $userHandle) {}

    public function buildUrl(EnvironmentInterface $environment): string
    {
        $user = $environment->getModel(UserModel::class, $this->userHandle);

        return '/users/' . $user->getId() . '/edit';
    }
}
```

Keep page and fixture classes in a dedicated directory alongside your specs:

```
tests/e2e/app/
├── Fixture/
│   ├── UserFixture.php
│   └── UserModel.php
└── Page/
    ├── LoginPage.php
    ├── UserEditPage.php
    └── UserListPage.php
```

## CLI reference

```bash
vendor/bin/tappet run [<suite>]                         # Run a suite (omit if a default is configured).
vendor/bin/tappet run --base-url=http://localhost:8080  # Override base URL.
vendor/bin/tappet run --filter=<pattern>                # Filter specs by pattern.
```

## Licence

[MIT](MIT-LICENSE.txt)

[Cypress adapter]: https://github.com/nytris/tappet-cypress
[Tappet Bundle]: https://github.com/nytris/tappet-bundle
