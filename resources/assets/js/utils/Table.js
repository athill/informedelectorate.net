import React from 'react';
import { Table as BsTable } from 'react-bootstrap';
import _ from 'lodash';

import { DATE_DISPLAY_FORMAT } from './';
import { sortByDate, sortByLink, sortByText } from './comparators';

export default class Table extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            data: this.props.data
        };

        this._sort = this._sort.bind(this);
    }

    componentDidMount() {
        this._sort(2);
    }

    _sort(index, ascending=true) {
        this.setState({
            data: this.state.data.slice().sort(sortByDate(index, DATE_DISPLAY_FORMAT))
        });
    }

    render() {
        const {headers} = this.props;
        if (this.state.data.length) {
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
