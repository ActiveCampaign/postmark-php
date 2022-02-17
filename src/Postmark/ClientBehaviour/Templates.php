<?php

declare(strict_types=1);

namespace Postmark\ClientBehaviour;

use Postmark\Models\DynamicResponseModel;

use function sprintf;

/** @internal \Postmark */
trait Templates
{
    /**
     * Delete a template.
     *
     * @param non-empty-string $id The ID or alias of the template to delete.
     */
    public function deleteTemplate(string $id): DynamicResponseModel
    {
        return new DynamicResponseModel(
            $this->processRestRequest('DELETE', sprintf('/templates/%s', $id))
        );
    }

    /**
     * Create a template
     *
     * @param string      $name           The friendly name for this template.
     * @param string      $subject        The template to be used for the 'subject' of emails sent using this template.
     * @param string      $htmlBody       The template to be used for the 'htmlBody' of emails sent using this template,
     *                                    optional if 'textBody' is not NULL.
     * @param string      $textBody       The template to be used for the 'textBody' of emails sent using this template,
     *                                    optional if 'htmlBody' is not NULL.
     * @param string|null $alias          An optional string you can provide to identify this Template. Allowed
     *                                    characters are numbers, ASCII letters, and ‘.’, ‘-’, ‘_’ characters, and the
     *                                    string has to start with a letter.
     * @param string      $templateType   Creates the template based on the template type provided. Possible options:
     *                                    Standard or Layout. Defaults to Standard.
     * @param string|null $layoutTemplate The alias of the Layout template that you want to use as layout for this
     *                                    Standard template. If not provided, a standard template will not use a layout
     *                                    template.
     */
    public function createTemplate(
        string $name,
        string $subject,
        string $htmlBody,
        string $textBody,
        ?string $alias = null,
        string $templateType = 'Standard',
        ?string $layoutTemplate = null
    ): DynamicResponseModel {
        $template = [];
        $template['name'] = $name;
        $template['subject'] = $subject;
        $template['htmlBody'] = $htmlBody;
        $template['textBody'] = $textBody;
        $template['alias'] = $alias;
        $template['templateType'] = $templateType;
        $template['layoutTemplate'] = $layoutTemplate;

        return new DynamicResponseModel($this->processRestRequest('POST', '/templates', $template));
    }

    /**
     * Edit a template
     *
     * @param string      $id             The ID or alias of the template you wish to update.
     * @param string|null $name           The friendly name for this template.
     * @param string|null $subject        The template to be used for the 'subject' of emails sent using this template.
     * @param string|null $htmlBody       The template to be used for the 'htmlBody' of emails sent using this template.
     * @param string|null $textBody       The template to be used for the 'textBody' of emails sent using this template.
     * @param string|null $alias          An optional string you can provide to identify this Template. Allowed
     *                                    characters are numbers, ASCII letters, and ‘.’, ‘-’, ‘_’ characters, and the
     *                                    string has to start with a letter.
     * @param string|null $layoutTemplate The alias of the Layout template that you want to use as layout for this
     *                                    Standard template. If not provided, a standard template will not use a layout
     *                                    template.
     */
    public function editTemplate(
        string $id,
        ?string $name = null,
        ?string $subject = null,
        ?string $htmlBody = null,
        ?string $textBody = null,
        ?string $alias = null,
        ?string $layoutTemplate = null
    ): DynamicResponseModel {
        $template = [];
        $template['name'] = $name;
        $template['subject'] = $subject;
        $template['htmlBody'] = $htmlBody;
        $template['textBody'] = $textBody;
        $template['alias'] = $alias;
        $template['layoutTemplate'] = $layoutTemplate;

        return new DynamicResponseModel(
            $this->processRestRequest('PUT', sprintf('/templates/%s', $id), $template)
        );
    }

    /**
     * Get the current information for a specific template.
     *
     * @param string $id the Id or alias for the template info you wish to retrieve.
     */
    public function getTemplate(string $id): DynamicResponseModel
    {
        return new DynamicResponseModel(
            $this->processRestRequest('GET', sprintf('/templates/%s', $id))
        );
    }

    /**
     * Get all templates associated with the Server.
     *
     * @param int         $count          The total number of templates to get at once (default is 100)
     * @param int         $offset         The number of templates to "Skip" before returning results.
     * @param string      $templateType   Filters the results based on the template type provided. Possible options:
     *                                    Standard, Layout, All. Defaults to All.
     * @param string|null $layoutTemplate Filters the results based on the layout template alias. Defaults to NULL.
     */
    public function listTemplates(
        int $count = 100,
        int $offset = 0,
        string $templateType = 'All',
        ?string $layoutTemplate = null
    ): DynamicResponseModel {
        $query = [];

        $query['count'] = $count;
        $query['offset'] = $offset;
        $query['templateType'] = $templateType;
        $query['layoutTemplate'] = $layoutTemplate;

        return new DynamicResponseModel($this->processRestRequest('GET', '/templates', $query));
    }

    /**
     * Confirm that your template content can be parsed/rendered…
     * …get a test rendering of your template, and a suggested model to use with your templates.
     *
     * @param string|null $subject                    The Subject template you wish to test.
     * @param string|null $htmlBody                   The HTML template you wish to test
     * @param string|null $textBody                   The number of templates to "Skip" before returning results.
     * @param object|null $testRenderModel            The model to be used when doing test renders of the templates
     *                                                that successfully parse in this request.
     * @param bool        $inlineCssForHtmlTestRender If htmlBody is specified, the test render will automatically do
     *                                                CSS Inlining for the HTML content. You may opt-out of this
     *                                                behavior by passing 'false' for this parameter.
     * @param string      $templateType               Validates templates based on template type (layout template or
     *                                                standard template). Possible options: Standard or Layout.
     *                                                Defaults to Standard.
     * @param string|null $layoutTemplate             An optional string to specify which layout template alias to use
     *                                                to validate a standard template. If not provided a standard
     *                                                template will not use a layout template.
     */
    public function validateTemplate(
        ?string $subject = null,
        ?string $htmlBody = null,
        ?string $textBody = null,
        ?object $testRenderModel = null,
        bool $inlineCssForHtmlTestRender = true,
        string $templateType = 'Standard',
        ?string $layoutTemplate = null
    ): DynamicResponseModel {
        $query = [];

        $query['subject'] = $subject;
        $query['htmlBody'] = $htmlBody;
        $query['textBody'] = $textBody;
        $query['testRenderModel'] = $testRenderModel;
        $query['inlineCssForHtmlTestRender'] = $inlineCssForHtmlTestRender;
        $query['templateType'] = $templateType;
        $query['layoutTemplate'] = $layoutTemplate;

        return new DynamicResponseModel($this->processRestRequest('POST', '/templates/validate', $query));
    }
}
