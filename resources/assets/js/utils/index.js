import moment from 'moment';
import React from 'react';
import { Table as BsTable} from 'react-bootstrap';
import $ from 'jquery';

export const NETWORK_FAILURE_ALERT = '<div class="alert alert-danger" role="alert">Something went wrong, please try again later.</div>';
export const LOADING_ICON = '<i class="fa fa-cog fa-spin fa-2x fa-fw"></i>';

export const formatDate = date => {
	return moment(date).format("MMMM Do YYYY, h:mm:ss a");
}

export const LoadingIcon = () => (
    <i className="fa fa-cog fa-spin fa-2x fa-fw"></i>
);

export function getParameterByName(name, query) {
    if (!query) {
      query = window.location.search;
    }
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(query);
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

export const Table = ({headers, data=[]}) => {
    if (data.length) {
        return (
            <BsTable responsive hover>
                <thead>
                    <tr>
                        {
                            headers.map(header => <th key={header}>{header}</th>)
                        }
                    </tr>
                </thead>
                <tbody>
                    {
                        data.map((row, i) => (
                            <tr key={i}>
                                {
                                    row.map((cell, j) => <td key={j}>{cell}</td>)
                                }
                            </tr>
                        ))
                    }
                </tbody>
            </BsTable>
        );
    } else {
        return null;
    }

};

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

