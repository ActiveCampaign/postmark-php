<?php

namespace Postmark\Models;

class TemplateValidationResponse
{
    public bool $AllContentIsValid;
    public HtmlBody $HtmlBody; // HtmlBody
    public TextBody $TextBody; // TextBody
    public Subject $Subject; // Subject
    public ?array $SuggestedTemplateModel;

    public function __construct(array $values = [])
    {
        $this->AllContentIsValid = !empty($values['AllContentIsValid']) ? $values['AllContentIsValid'] : false;
        $this->HtmlBody = !empty($values['HtmlBody']) ? new HtmlBody($values['HtmlBody']) : new HtmlBody();
        $this->TextBody = !empty($values['TextBody']) ? new TextBody($values['TextBody']) : new TextBody();
        $this->Subject = !empty($values['Subject']) ? new Subject($values['Subject']) : new Subject();
        $this->SuggestedTemplateModel = !empty($values['SuggestedTemplateModel']) ? $values['SuggestedTemplateModel'] : [];
    }

    public function isAllContentIsValid(): bool
    {
        return $this->AllContentIsValid;
    }

    public function setAllContentIsValid(bool $AllContentIsValid): TemplateValidationResponse
    {
        $this->AllContentIsValid = $AllContentIsValid;

        return $this;
    }

    public function getHtmlBody(): HtmlBody
    {
        return $this->HtmlBody;
    }

    public function setHtmlBody(HtmlBody $HtmlBody): TemplateValidationResponse
    {
        $this->HtmlBody = $HtmlBody;

        return $this;
    }

    public function getTextBody(): TextBody
    {
        return $this->TextBody;
    }

    public function setTextBody(TextBody $TextBody): TemplateValidationResponse
    {
        $this->TextBody = $TextBody;

        return $this;
    }

    public function getSubject(): Subject
    {
        return $this->Subject;
    }

    public function setSubject(Subject $Subject): TemplateValidationResponse
    {
        $this->Subject = $Subject;

        return $this;
    }

    public function getSuggestedTemplateModel(): array
    {
        return $this->SuggestedTemplateModel;
    }

    public function setSuggestedTemplateModel(array $SuggestedTemplateModel): TemplateValidationResponse
    {
        $this->SuggestedTemplateModel = $SuggestedTemplateModel;

        return $this;
    }
}

class HtmlBody
{
    public bool $ContentIsValid;
    public array $ValidationErrors; // array( ValidationError )
    public string $RenderedContent;

    public function __construct(array $values = [])
    {
        $this->ContentIsValid = !empty($values['ContentIsValid']) ? $values['ContentIsValid'] : false;
        $this->RenderedContent = !empty($values['RenderedContent']) ? $values['RenderedContent'] : '';
        !empty($values['ValidationErrors']) ? $this->setValidationErrors($values['ValidationErrors']) : [new ValidationError()];
    }

    public function isContentIsValid(): bool
    {
        return $this->ContentIsValid;
    }

    public function setContentIsValid(bool $ContentIsValid): HtmlBody
    {
        $this->ContentIsValid = $ContentIsValid;

        return $this;
    }

    public function getValidationErrors(): array
    {
        return $this->ValidationErrors;
    }

    public function setValidationErrors(array $ValidationErrors): HtmlBody
    {
        $tempArray = [];
        foreach ($ValidationErrors as $value) {
            $temp = new ValidationError($value);
            $tempArray[] = $temp;
        }
        $this->ValidationErrors = $tempArray;

        return $this;
    }

    public function getRenderedContent(): string
    {
        return $this->RenderedContent;
    }

    public function setRenderedContent(string $RenderedContent): HtmlBody
    {
        $this->RenderedContent = $RenderedContent;

        return $this;
    }
}

class ValidationError
{
    public string $Message;
    public int $Line;
    public int $CharacterPosition;

    public function __construct(array $values = [])
    {
        $this->Message = !empty($values['Message']) ? $values['Message'] : '';
        $this->Line = !empty($values['Line']) ? $values['Line'] : 0;
        $this->CharacterPosition = !empty($values['CharacterPosition']) ? $values['CharacterPosition'] : 0;
    }

    public function getMessage(): string
    {
        return $this->Message;
    }

    public function setMessage(string $Message): ValidationError
    {
        $this->Message = $Message;

        return $this;
    }

    public function getLine(): int
    {
        return $this->Line;
    }

    public function setLine(int $Line): ValidationError
    {
        $this->Line = $Line;

        return $this;
    }

    public function getCharacterPosition(): int
    {
        return $this->CharacterPosition;
    }

    public function setCharacterPosition(int $CharacterPosition): ValidationError
    {
        $this->CharacterPosition = $CharacterPosition;

        return $this;
    }
}

class TextBody
{
    public bool $ContentIsValid;
    public array $ValidationErrors; // array( ValidationError )
    public string $RenderedContent;

    public function __construct(array $values = [])
    {
        $this->ContentIsValid = !empty($values['ContentIsValid']) ? $values['ContentIsValid'] : false;
        $this->RenderedContent = !empty($values['RenderedContent']) ? $values['RenderedContent'] : 0;
        !empty($values['ValidationErrors']) ? $this->setValidationErrors($values['ValidationErrors']) : [new ValidationError()];
    }

    public function isContentIsValid(): bool
    {
        return $this->ContentIsValid;
    }

    public function setContentIsValid(bool $ContentIsValid): TextBody
    {
        $this->ContentIsValid = $ContentIsValid;

        return $this;
    }

    public function getValidationErrors(): array
    {
        return $this->ValidationErrors;
    }

    public function setValidationErrors(array $ValidationErrors): TextBody
    {
        $tempArray = [];
        foreach ($ValidationErrors as $value) {
            $temp = new ValidationError($value);
            $tempArray[] = $temp;
        }
        $this->ValidationErrors = $tempArray;

        return $this;
    }

    public function getRenderedContent(): string
    {
        return $this->RenderedContent;
    }

    public function setRenderedContent(string $RenderedContent): TextBody
    {
        $this->RenderedContent = $RenderedContent;

        return $this;
    }
}

class Subject
{
    public bool $ContentIsValid;
    public array $ValidationErrors;  // array( ValidationError )
    public string $RenderedContent;

    public function __construct(array $values = [])
    {
        $this->ContentIsValid = !empty($values['ContentIsValid']) ? $values['ContentIsValid'] : false;
        $this->RenderedContent = !empty($values['RenderedContent']) ? $values['RenderedContent'] : '';
        !empty($values['ValidationErrors']) ? $this->setValidationErrors($values['ValidationErrors']) : [new ValidationError()];
    }

    public function isContentIsValid(): bool
    {
        return $this->ContentIsValid;
    }

    public function setContentIsValid(bool $ContentIsValid): Subject
    {
        $this->ContentIsValid = $ContentIsValid;

        return $this;
    }

    public function getValidationErrors(): array
    {
        return $this->ValidationErrors;
    }

    public function setValidationErrors(array $ValidationErrors): Subject
    {
        $tempArray = [];
        foreach ($ValidationErrors as $value) {
            $temp = new ValidationError($value);
            $tempArray[] = $temp;
        }
        $this->ValidationErrors = $tempArray;

        return $this;
    }

    public function getRenderedContent(): string
    {
        return $this->RenderedContent;
    }

    public function setRenderedContent(string $RenderedContent): Subject
    {
        $this->RenderedContent = $RenderedContent;

        return $this;
    }
}

// class Company {
//    public string $Address;
//    public string $Phone;
//    public string $Name;
//
//    /**
//     * @param array $values
//     */
//    public function __construct(array $values = [])
//    {
//        $this->Address = !empty($values['Address']) ? $values['Address'] : '';
//        $this->Phone = !empty($values['Phone']) ? $values['Phone'] : '';
//        $this->Name = !empty($values['Name']) ? $values['Name'] : '';
//    }
//
//    /**
//     * @return string
//     */
//    public function getAddress(): string
//    {
//        return $this->Address;
//    }
//
//    /**
//     * @param string $Address
//     * @return Company
//     */
//    public function setAddress(string $Address): Company
//    {
//        $this->Address = $Address;
//        return $this;
//    }
//
//    /**
//     * @return string
//     */
//    public function getPhone(): string
//    {
//        return $this->Phone;
//    }
//
//    /**
//     * @param string $Phone
//     * @return Company
//     */
//    public function setPhone(string $Phone): Company
//    {
//        $this->Phone = $Phone;
//        return $this;
//    }
//
//    /**
//     * @return string
//     */
//    public function getName(): string
//    {
//        return $this->Name;
//    }
//
//    /**
//     * @param string $Name
//     * @return Company
//     */
//    public function setName(string $Name): Company
//    {
//        $this->Name = $Name;
//        return $this;
//    }
// }

// class Person {
//    public string $Name;
//
//    /**
//     * @param array $values
//     */
//    public function __construct(array $values = [])
//    {
//        $this->Name = !empty($values['Name']) ? $values['Name'] : '';
//    }
//
//    /**
//     * @return string
//     */
//    public function getName(): string
//    {
//        return $this->Name;
//    }
//
//    /**
//     * @param string $name
//     * @return Person
//     */
//    public function setName(string $name): Person
//    {
//        $this->Name = $name;
//        return $this;
//    }
//
// }
//
// class SuggestedTemplateModel {
//    public string $UserName;
//    public Company $Company; //Company
//    public People $Person; //array( Person )
//    public string $SubjectHeadline;
//
//    /**
//     * @param array $values
//     */
//    public function __construct(array $values = [])
//    {
//        $this->UserName = !empty($values['UserName']) ? $values['UserName'] : 0;
//        $this->Company = !empty($values['Company']) ? new Company($values) : new Company();
//        $this->Person = !empty($values['Person']) ? new Person($values) : new Person();
//        $this->SubjectHeadline = !empty($values['SubjectHeadline']) ? $values['SubjectHeadline'] : 0;
//    }
//
//    /**
//     * @return int|mixed|string
//     */
//    public function getUserName(): mixed
//    {
//        return $this->UserName;
//    }
//
//    /**
//     * @param int|mixed|string $UserName
//     * @return SuggestedTemplateModel
//     */
//    public function setUserName(mixed $UserName): SuggestedTemplateModel
//    {
//        $this->UserName = $UserName;
//        return $this;
//    }
//
//    /**
//     * @return int|mixed|Company
//     */
//    public function getCompany(): mixed
//    {
//        return $this->Company;
//    }
//
//    /**
//     * @param int|mixed|Company $Company
//     * @return SuggestedTemplateModel
//     */
//    public function setCompany(mixed $Company): SuggestedTemplateModel
//    {
//        $this->Company = $Company;
//        return $this;
//    }
//
//    /**
//     * @return int|mixed|Person
//     */
//    public function getPerson(): mixed
//    {
//        return $this->Person;
//    }
//
//    /**
//     * @param int|mixed|Person $Person
//     * @return SuggestedTemplateModel
//     */
//    public function setPerson(mixed $Person): SuggestedTemplateModel
//    {
//        $this->Person = $Person;
//        return $this;
//    }
//
//    /**
//     * @return int|mixed|string
//     */
//    public function getSubjectHeadline(): mixed
//    {
//        return $this->SubjectHeadline;
//    }
//
//    /**
//     * @param int|mixed|string $SubjectHeadline
//     * @return SuggestedTemplateModel
//     */
//    public function setSubjectHeadline(mixed $SubjectHeadline): SuggestedTemplateModel
//    {
//        $this->SubjectHeadline = $SubjectHeadline;
//        return $this;
//    }
// }
