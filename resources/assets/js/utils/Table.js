import _ from 'lodash';
import React from 'react';
import { Table as BsTable } from 'react-bootstrap';
import Icon from 'react-fontawesome';

import { DATE_DISPLAY_FORMAT } from './';
import { getSortByDate, sortByLink, sortByText } from './comparators';

export const ColumnTypes = {
    TEXT: 'TEXT',
    DATE: 'DATE',
    LINK: 'LINK'
};

const typeFuncMap = {
    [ColumnTypes.TEXT]: sortByText,
    [ColumnTypes.DATE]: getSortByDate(DATE_DISPLAY_FORMAT),
    [ColumnTypes.LINK]: sortByLink
};

export class Column {
    constructor(title, type) {
        this.title = title;
        if (!type in ColumnTypes) {
            console.error(`Illegal column type in Column: ${type}`);
        }
        this.type = type;
    }
}

const Header = ({title, onClick=e => e, ascending=null}) => {
    const icon = ascending === null ? 
        null :
        ascending ? <Icon name="chevron-down" />
            : <Icon name="chevron-up" />;   
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
            data: this.props.data,
            sortIndex: this.props.sortIndex || 0,
            sortDirection: this.props.sortDirection || 'asc'
        };

        this._sort = this._sort.bind(this);
        this._click = this._click.bind(this);
    }

    componentDidMount() {
        const {sortIndex, sortDirection} = this.state;
        this._sort(sortIndex, sortDirection === 'asc');
    }

    _sort(index, ascending=true) {
        const { columns } = this.props;
        const sortFunc = typeFuncMap[columns[index].type]
        this.setState({
            data: this.state.data.slice().sort(sortFunc(index, ascending)),
            sortIndex: index,
            sortDirection: ascending ? 'asc' : 'desc'
        });
    }

    _click(index) {
        return e => {
            if (index === this.state.sortIndex) {
                this._sort(index, !(this.state.sortDirection === 'asc'));
            } else {
                this._sort(index, true);
            }
        };
    }

    render() {
        const {columns} = this.props;
        if (this.state.data.length) {
            return (
                <BsTable responsive hover>
                    <thead>
                        <tr>
                            {
                                columns.map((column, i) => {
                                    let ascending = null;
                                    if (i === this.state.sortIndex) {
                                        ascending = this.state.sortDirection === 'asc';
                                    }
                                    return <Header key={i} title={column.title} onClick={this._click(i)} ascending={ascending} />;
                                })
                            }
                        </tr>
                    </thead>
                    <tbody>
                        {
                            this.state.data.map((row, i) => (
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
    }
};
Table.displayName = 'Table';
