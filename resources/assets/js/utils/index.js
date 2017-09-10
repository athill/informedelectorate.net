import moment from 'moment';
import React from 'react';
import $ from 'jquery';

export const NETWORK_FAILURE_ALERT = '<div class="alert alert-danger" role="alert">Something went wrong, please try again later.</div>';
export const LOADING_ICON = '<i class="fa fa-cog fa-spin fa-2x fa-fw"></i>';

export const DATE_DISPLAY_FORMAT = "MMMM Do, YYYY, h:mm:ss a";
export const DATE_ONLY_DISPLAY_FORMAT = "MMMM Do, YYYY";

export const formatDate = date => {
	return moment(date).format(DATE_DISPLAY_FORMAT);
};

export const formatDateOnly = date => {
    return moment(date).format(DATE_ONLY_DISPLAY_FORMAT);
};

export const Address = ({ addrs }) => (
    <div>
        {
            addrs.map((addr, i) => (
                <p key={i}>
                    { addr.locationName && <span>{ addr.locationName }<br /></span>}
                    { addr.line1 }<br />
                    { addr.line2 && <span>{ addr.line2 }<br /></span> }
                    {addr.city}, {addr.state} {addr.zip}
                </p>
            ))
        }
    </div>
);

export const LoadingIcon = () => (
    <i className="fa fa-cog fa-spin fa-2x fa-fw"></i>
);

export const Phone = ({number}) => (
    <a href={`tel:${number.replace(/[^0-9]/g, '')}`}>{number}</a>
);

export const Email = ({email}) => (
    //// todo, obfuscate
    <a href={`mailto:${email}`}>{email}</a>
);

export const DefinitionList = ({items}) => (
    <dl className="dl-horizontal">
        {
            items.map(item => (
                <div key={item.key}>
                    <dt>{item.key}:</dt>
                    <dd>{item.value}</dd>
                </div>
            ))
        }
    </dl>
);

export const getQuerystringObject = query => {
    if (!query) {
      query = window.location.search;
    }
    const params = {};
    query = query.replace(/^\?/, '');
    const args = query.split('&');
    args.forEach(arg => {
        const [name, val] = arg.split('=');
        const value = decodeURIComponent(val);
        params[name] = name in params ?
            (Array.isArray(params[name]) ? 
                             params[name].concat(value) : 
                             [params[name]].concat(value)) :
            value;
    });
    return params;    
} 

export const getParameterByName = (name, query) => {
    const params = getQuerystringObject(query);
    return name in params ? params[name] : '';
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

export const getTableRowObject = (tds, rowAtts={})  => {
    const $row = $(`<tr ${$.param(rowAtts)} />`);
    tds.forEach(td => {
        if (typeof td === 'string') {
            $row.append(`<td>${td}</td>`);
        } else {
            if (!td.content) {
                console.error('error with td: ', td);
                $row.append('<td />');
            } else {
                const atts = td.atts || {};
                $row.append(`<td ${$.param(atts)}>${td.content}</td>`);                
            }
        }
    });
    return $row;
}

