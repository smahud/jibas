// input validator

class Vldr
{
    static HasOption(idSelect, inputName = "")
    {
        let n = $("#" + idSelect + " option").length;
        if (n === 0)
        {
            alert(inputName + " belum ada data");
            $("#" + idSelect).focus();
            return false;
        }

        return true;
    }

    static InputText(idInputText, inputName, minLength = 0, maxLength = 0)
    {
        let element = $("#" + idInputText);

        let text = $.trim(element.val());
        if (minLength === 0)
        {
            if (text.length === 0)
            {
                alert(inputName + " belum ditentukan");
                element.focus();
                return false;
            }
        }
        else if (minLength > 0)
        {
            if (text.length < minLength)
            {
                alert("Panjang " + inputName + " minimal " + minLength + " karakter");
                element.focus();
                return false;
            }
        }

        if (maxLength > 0)
        {
            if (text.length > maxLength)
            {
                alert("Panjang " + inputName + " maksimal " + maxLength + " karakter");
                element.focus();
                return false;
            }
        }

        return true;
    }

    static MaxText(idInputText, maxLength, inputName = "")
    {
        let element = $("#" + idInputText);
        let text = $.trim(element.val());
        if (text.length > maxLength)
        {
            if (inputName !== "")
                alert("Panjang " + inputName + " maksimal " + maxLength + " karakter");
            else
                alert("Panjang teks maksimal " + maxLength + " karakter");
            
            element.focus();
            return false;
        }

        return true;
    }

    static IsNumeric(idElement, elementName = "")
    {
        let element = $("#" + idElement);
        let str = $.trim(element.val());
        if (isNaN(Number(str)))
        {
            if (elementName !== "")
            {
                alert(elementName + " harus numerik");
                element.focus();
            }

            return false;
        }

        return true;
    }

    static IsInRangeNumeric(idElement, elementName = "", minVal, maxVal)
    {
        let element = $("#" + idElement);
        let str = $.trim(element.val());
        const num = Number(str);
        if (isNaN(num) || num < minVal || num > maxVal)
        {
            if (elementName !== "")
                alert(elementName + " harus angka antara " + minVal + " dan " + maxVal);
            else
                alert("Angka harus antara " + minVal + " dan " + maxVal);

            element.focus();
            return false;
        }

        return true;
    }

    static IsInRangeNumericValue(valueStr, valueName = "", minVal, maxVal)
    {
        const num = Number(valueStr);
        if (isNaN(num) || num < minVal || num > maxVal)
        {
            if (valueName !== "")
                alert(valueName + " harus angka antara " + minVal + " dan " + maxVal);
            else
                alert("Angka harus antara " + minVal + " dan " + maxVal);

            return false;
        }

        return true;
    }


    static IsInteger(idElement, elementName = "")
    {
        let element = $("#" + idElement);
        let str = $.trim(element.val());
        const num = Number(str);
        if (!Number.isInteger(num))
        {
            if (elementName !== "")
            {
                alert(elementName + " harus bilangan bulat");
                element.focus();
            }

            return false;
        }

        return true;
    }

    static IsInRangeInteger(idElement, elementName = "", minVal, maxVal)
    {
        let element = $("#" + idElement);
        let str = $.trim(element.val());
        const num = Number(str);
        if (!Number.isInteger(num) || num < minVal || num > maxVal)
        {
            if (elementName !== "")
                alert(elementName + " harus bilangan bulat antara " + minVal + " dan " + maxVal);
            else
                alert("Bilangan harus antara " + minVal + " dan " + maxVal);

            element.focus();
            return false;
        }

        return true;
    }

    static IsInRangeIntegerValue(valueStr, valueName = "", minVal, maxVal)
    {
        const num = Number(valueStr);
        if (!Number.isInteger(num) || num < minVal || num > maxVal)
        {
            if (valueName !== "")
                alert(valueName + " harus bilangan bulat antara " + minVal + " dan " + maxVal);
            else
                alert("Bilangan harus antara " + minVal + " dan " + maxVal);

            return false;
        }

        return true;
    }
    
    static IsHaveInput(idElement, elementName = "")
    {
        let element = $("#" + idElement);
        let str = $.trim(element.val());
        if (str.length === 0)
        {
            if (elementName !== "")
                alert(elementName + " belum ditentukan");

            element.focus();
            return false;
        }

        return true;
    }

    static IsNumericValue(valueStr, valueName = "")
    {
        if (isNaN(Number(valueStr)))
        {
            if (valueName !== "")
                alert(valueName + " harus numerik");

            return false;
        }

        return true;
    }

    static IsIntegerValue(valueStr, valueName = "")
    {
        const num = Number(valueStr);
        if (!Number.isInteger(num))
        {
            if (valueName !== "")
                alert(valueName + " harus bilangan bulat");

            return false;
        }

        return true;
    }

    static IsNotEmpty(idElement, elementName = "")
    {
        let element = $("#" + idElement);
        let valueStr = $.trim(element.val());
        if (valueStr.length === 0)
        {
            if (elementName !== "")
                alert(elementName + " belum ditentukan");

            element.focus();
            return false;
        }

        return true;
    }

    static IsNotEmptyValue(valueStr, valueName = "")
    {
        valueStr = $.trim(valueStr);
        if (valueStr.length === 0)
        {
            if (valueName !== "")
                alert(valueName + " belum ditentukan");

            return false;
        }

        return true;
    }

    static IsNotNegative(idElement, elementName = "")
    {
        let element = $("#" + idElement);
        let valueStr = $.trim(element.val());
        const num = Number(valueStr);
        if (num < 0)
        {
            if (elementName !== "")
                alert(elementName + " tidak boleh negatif");

            return false;
        }

        return true;
    }

    static IsNotNegativeValue(valueStr, valueName = "")
    {
        const num = Number(valueStr);
        if (num < 0)
        {
            if (valueName !== "")
                alert(valueName + " tidak boleh negatif");

            return false;
        }

        return true;
    }

    static IsNotZero(idElement, elementName = "")
    {
        let element = $("#" + idElement);
        let valueStr = $.trim(element.val());
        const num = Number(valueStr);
        if (num === 0)
        {
            if (elementName !== "")
                alert(elementName + " tidak boleh nol");

            return false;
        }

        return true;
    }

    static IsNotZeroValue(valueStr, valueName = "")
    {
        const num = Number(valueStr);
        if (num === 0)
        {
            if (valueName !== "")
                alert(valueName + " tidak boleh nol");

            return false;
        }

        return true;
    }

    static IsPositive(idElement, elementName = "")
    {
        let element = $("#" + idElement);
        let valueStr = $.trim(element.val());
        const num = Number(valueStr);
        if (num <= 0)
        {
            if (elementName !== "")
                alert(elementName + " tidak boleh nol atau negatif");

            return false;
        }

        return true;
    }

    static IsPositiveValue(valueStr, valueName = "")
    {
        const num = Number(valueStr);
        if (num <= 0)
        {
            if (valueName !== "")
                alert(valueName + " tidak boleh nol atau negatif");

            return false;
        }

        return true;
    }

     static IsZeroPositive(idElement, elementName = "")
    {
        let element = $("#" + idElement);
        let valueStr = $.trim(element.val());
        const num = Number(valueStr);
        if (num < 0)
        {
            if (elementName !== "")
                alert(elementName + " tidak boleh negatif");

            return false;
        }

        return true;
    }

    static IsZeroPositiveValue(valueStr, valueName = "")
    {
        const num = Number(valueStr);
        if (num < 0)
        {
            if (valueName !== "")
                alert(valueName + " tidak boleh negatif");

            return false;
        }

        return true;
    }
}