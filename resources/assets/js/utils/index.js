import moment from 'moment';
import $ from 'jquery';

export const NETWORK_FAILURE_ALERT = '<div class="alert alert-danger" role="alert">Something went wrong, please try again later.</div>';
export const LOADING_ICON = '<i class="fa fa-cog fa-spin fa-2x fa-fw"></i>';

export const formatDate = date => {
	return moment(date).format("MMMM Do YYYY, h:mm:ss a");
}

export function getParameterByName(name, url) {
    if (!url) {
      url = window.location.href;
    }
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
};

export function getTableObject(headers) {
    const $tablewrap = $('<div class="table-responsive" />');
    const $table = $('<table class="table table-hover" />');
    $tablewrap.append($table);
    const $tr = $('<tr />');
    const $thead = $('<thead />');
    $thead.append($tr);
    headers.forEach(header => $tr.append(`<th>${header}</th>`));
    const $tbody = $('<tbody />');
    $table.append($thead, $tbody);
    $tablewrap.append($table);
    return {
        $table: $tablewrap,
        $tbody
    };
}

