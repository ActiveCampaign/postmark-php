<?php

declare(strict_types=1);

namespace Postmark\ClientBehaviour;

use Postmark\Models\DynamicResponseModel;
use Postmark\PostmarkClient;

use function sprintf;

/**
 * @internal \Postmark
 *
 * @see PostmarkClient
 *
 * @psalm-import-type TemplateId from PostmarkClient
 * @psalm-import-type TemplateModel from PostmarkClient
 */
trait Templates
{
    /**
     * Delete a template.
     *
     * @param string|int $id The ID or alias of the template to delete.
     * @psalm-param TemplateId $id
     */
    public function deleteTemplate($id): DynamicResponseModel // phpcs:ignore
    {
        return new DynamicResponseModel(
            $this->processRestRequest('DELETE', sprintf('/templates/%s', $id)),
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
        string|null $alias = null,
        string $templateType = 'Standard',
        string|null $layoutTemplate = null,
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
     * @param TemplateId  $id             The ID or alias of the template you wish to update.
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
        $id,
        string|null $name = null,
        string|null $subject = null,
        string|null $htmlBody = null,
        string|null $textBody = null,
        string|null $alias = null,
        string|null $layoutTemplate = null,
    ): DynamicResponseModel {
        $template = [];
        $template['name'] = $name;
        $template['subject'] = $subject;
        $template['htmlBody'] = $htmlBody;
        $template['textBody'] = $textBody;
        $template['alias'] = $alias;
        $template['layoutTemplate'] = $layoutTemplate;

        return new DynamicResponseModel(
            $this->processRestRequest('PUT', sprintf('/templates/%s', $id), $template),
        );
    }

    /**
     * Get the current information for a specific template.
     *
     * @param string|int $id the Id or alias for the template info you wish to retrieve.
     * @psalm-param TemplateId $id
     */
    public function getTemplate($id): DynamicResponseModel // phpcs:ignore
    {
        return new DynamicResponseModel(
            $this->processRestRequest('GET', sprintf('/templates/%s', $id)),
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
        string|null $layoutTemplate = null,
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
     * @param string|null        $subject         The Subject template you wish to test.
     * @param string|null        $htmlBody        The HTML template you wish to test
     * @param string|null        $textBody        The number of templates to "Skip" before returning results.
     * @param TemplateModel|null $testRenderModel The model to be used when doing test renders of the templates
     *                                            that successfully parse in this request.
     * @param bool               $inlineCss       If htmlBody is specified, the test render will automatically do
     *                                            CSS Inlining for the HTML content. You may opt-out of this
     *                                            behavior by passing 'false' for this parameter.
     * @param string             $templateType    Validates templates based on template type (layout template or
     *                                            standard template). Possible options: Standard or Layout.
     *                                            Defaults to Standard.
     * @param string|null        $layoutTemplate  An optional string to specify which layout template alias to use
     *                                            to validate a standard template. If not provided a standard
     *                                            template will not use a layout template.
     */
    public function validateTemplate(
        string|null $subject = null,
        string|null $htmlBody = null,
        string|null $textBody = null,
        $testRenderModel = null,
        bool $inlineCss = true,
        string $templateType = 'Standard',
        string|null $layoutTemplate = null,
    ): DynamicResponseModel {
        $query = [];

        $query['subject'] = $subject;
        $query['htmlBody'] = $htmlBody;
        $query['textBody'] = $textBody;
        $query['testRenderModel'] = $testRenderModel;
        $query['inlineCssForHtmlTestRender'] = $inlineCss;
        $query['templateType'] = $templateType;
        $query['layoutTemplate'] = $layoutTemplate;

        return new DynamicResponseModel($this->processRestRequest('POST', '/templates/validate', $query));
    }
}
