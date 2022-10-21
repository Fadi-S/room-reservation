export default function useQueryStringToJSON(queryString) {
    if (queryString.indexOf("?") > -1) {
        queryString = queryString.split("?")[1];
    }

    queryString = decodeURI(queryString);

    let pairs = queryString.split("&");
    let result = {};

    pairs.forEach(function (pair) {
        pair = pair.split("=");
        if (!pair[0] && !pair[1]) return;

        result[pair[0]] = decodeURIComponent(pair[1] || "");
    });

    return result;
}
