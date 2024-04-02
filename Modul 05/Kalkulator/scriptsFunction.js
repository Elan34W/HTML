let result = document.getElementById('screen');

const display = (a) => {
    if (a == 'clear') {
        result.value = '';
    } else if (a == 'del') {
        result.value = result.value.slice(0, -1);
    } else if (a == 'action') {
        try {
            result.value = eval(prepareInput(result.value));
            result.value = cleanOutput(result.value);
        } catch (error) {
            alert('Error');
        }
    } else {
        if (validateInput(a)) {
            result.value += a;
        }
    }
};

function cleanOutput(output) {
    let output_string = output.toString();
    let decimal = output_string.split(".")[1];
    output_string = output_string.split(".")[0];

    let output_array = output_string.split("");

    if (output_array.length > 3) {
        for (let i = output_array.length - 3; i > 0; i -= 3) {
            output_array.splice(i, 0, ",");
        }
    }

    if (decimal) {
        output_array.push(".");
        output_array.push(decimal);
    }

    return output_array.join("");
}

function validateInput(value) {
    let last_input = result.value.trim().slice(-1);
    let operators = ["+", "-", "*", "/"];

    if (operators.includes(value) && operators.includes(last_input)) {
        return false;
    }

    if (value == "." && last_input == ".") {
        return false;
    }

    if (operators.includes(value)) {
        if (operators.includes(last_input)) {
            return false;
        } else {
            return true;
        }
    }

    return true;
}

function prepareInput(input) {
    let input_array = input.split("");

    for (let i = 0; i < input_array.length; i++) {
        if (input_array[i] == "%") {
            input_array[i] = "/100";
        }
    }
    return input_array.join("");
}
