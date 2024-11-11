htmx.config.responseHandling = [
    {code:"204", swap: false},   // 204 - No Content by default does nothing, but is not an error
    {code:"[23]..", swap: true}, // 200 & 300 responses are non-errors and are swapped
    {code:"[45]..", swap: true, error:true}, // 400 & 500 responses are not swapped and are errors
    {code:"...", swap: false}    // catch all for any other response code
];