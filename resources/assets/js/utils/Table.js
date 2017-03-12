import _ from 'lodash';
import React from 'react';
import { Table as BsTable } from 'react-bootstrap';
import Icon from 'react-fontawesome';

import { DATE_DISPLAY_FORMAT } from './';
import { sortByDate, sortByLink, sortByText } from './comparators';

// const Header = ({title, onClick=e => e, ascending=null}) => {
//     const icon = ascending === null ? 
//         null :
//         ascending ? <Icon name="chevron-down" />
//             : <Icon name="chevron-up" />;
//     return (
//         <th>
//             {/* <a href="" onClick={e => { e.preventDefault(); onClick(e); }}>{ title }</a>

//             {{ icon }}
//         */}
//         </th>
//     );
// };


export const ColumnTypes = {
    TEXT: 'TEXT',
    DATE: 'DATE',
    LINK: 'LINK'
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

// <Header key={i} title={header} ascending={true} onClick={ e => e } />

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
        this._sort(3);
    }

    _sort(index, ascending=true) {
        this.setState({
            data: this.state.data.slice().sort(sortByText(index, false))
        });
    }

    _click(index) {
        return e => {
            console.log(index);
        };
    }

    render() {
        const {headers} = this.props;
        if (this.state.data.length) {
            return (
                <BsTable responsive hover>
                    <thead>
                        <tr>
                            {
                                headers.map((header, i) => <Header key={i} title={header} onClick={this._click(i)} ascending={true} />)
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
