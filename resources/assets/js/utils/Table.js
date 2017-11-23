import _ from 'lodash';
import React from 'react';
import { Table as BsTable, Pagination  } from 'react-bootstrap';
import Icon from 'react-fontawesome';

import { DATE_DISPLAY_FORMAT } from './';
import { getSortByDate, sortByLink, sortByText } from './comparators';

//// valid column types for sorting
export const ColumnTypes = {
    TEXT: 'TEXT',
    DATE: 'DATE',
    LINK: 'LINK'
};

//// map column types to sorting algorithms
const typeFuncMap = {
    [ColumnTypes.TEXT]: sortByText,
    [ColumnTypes.DATE]: getSortByDate(DATE_DISPLAY_FORMAT),
    [ColumnTypes.LINK]: sortByLink
};

//// encapsulates table column metadata
export class Column {
    constructor(title, type) {
        this.title = title;
        if (!type in ColumnTypes) {
            console.error(`Illegal column type in Column: ${type}`);
        }
        this.type = type;
    }
};

//// components
const Header = ({title, onClick=e => e, ascending=null}) => {
    const icon = ascending === null ? 
        null :
        ascending ? 
            <Icon name="chevron-down" /> :
            <Icon name="chevron-up" />;   
    return (
        <th onClick={onClick} style={{ cursor: 'pointer' }}>
            { title }

            <span className="pull-right">{ icon }</span>
        </th>
    );
};

export default class Table extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            //// sorting
            sortedData: this.props.data || [],
            sortIndex: this.props.sortIndex || 0,
            sortDirection: this.props.sortDirection || 'asc',
            //// pagination
            activePage: 1,
            offset: 0
        };

        //// move these into state if page size becomes changable
        this.pageSize = this.props.pageSize || 50;
        this.numPages = Math.floor(this.props.data.length / this.pageSize);

        //// bind 'this' to custom methods
        this._sort = this._sort.bind(this);
        this._sortClick = this._sortClick.bind(this);
        this._navigate = this._navigate.bind(this);
        this._paginator = this._paginator.bind(this);
    }

    componentDidMount() {
        const {sortIndex, sortDirection} = this.state;
        this._sort(sortIndex, sortDirection === 'asc');
    }

    _sort(index, ascending=true) {
        const { columns } = this.props;
        const sortFunc = typeFuncMap[columns[index].type]
        this.setState({
            sortedData: this.props.data.slice().sort(sortFunc(index, ascending)),
            sortIndex: index,
            sortDirection: ascending ? 'asc' : 'desc',
            offset: 0,
            activePage: 1
        });
    }

    _paginator() {
        return (
            <Pagination
                first
                last
                next
                prev
                boundaryLinks
                bsSize="small"
                maxButtons={5}
                items={this.numPages}
                activePage={this.state.activePage}
                onSelect={this._navigate} />
        );
    }    

    _navigate(pageNum) {
        const offset = (pageNum  * this.pageSize);
        this.setState({
            activePage: pageNum,
            offset
        });
    }

    _sortClick(index) {
        return e => {
            if (index === this.state.sortIndex) {
                this._sort(index, !(this.state.sortDirection === 'asc'));
            } else {
                this._sort(index, true);
            }
        };
    }

    render() {
        const { columns, data, queryLink } = this.props;
        const { offset, sortedData, sortDirection, sortIndex } = this.state;
        if (data.length) {
            const last = Math.min(offset + this.pageSize, data.length);
            const displayData = sortedData.slice(offset, last);
            return (
                <div>
                    <div className="clearfix">
                        <div className="pull-left">Showing {offset + 1} to {last} of {data.length} { queryLink && ['results for ', queryLink] }</div>
                        <nav className="pull-right">{ this._paginator() }</nav> 
                    </div>
                    <BsTable responsive hover>
                        <thead>
                            <tr>
                                {
                                    columns.map((column, i) => {
                                        let ascending = null;
                                        if (i === sortIndex) {
                                            ascending = sortDirection === 'asc';
                                        }
                                        return <Header key={i} title={column.title} onClick={this._sortClick(i)} ascending={ascending} />;
                                    })
                                }
                            </tr>
                        </thead>
                        <tbody>
                            {
                                displayData.map((row, i) => (
                                    <tr key={i}>
                                        {
                                            row.map((cell, j) => <td key={j}>{cell}</td>)
                                        }
                                    </tr>
                                ))
                            }
                        </tbody>
                    </BsTable>
                    <div className="pull-right">
                        { this._paginator() }
                    </div>
                </div>
            );
        } else {
            return null;
        }        
    }
};
Table.displayName = 'Table';
