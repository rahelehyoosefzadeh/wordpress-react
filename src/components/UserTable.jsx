import React, { useState } from 'react';
import { Link } from 'react-router-dom';

const UserTable = (props) => {
    const [selectedUser, setSelectedUser] = useState(null);

    const handleUserClick = async (user) => {
        const response = await fetch(`${props.data.siteUrl}/wp-json/inpsyde/v1/details?id=${user.id}`);
        const json = await response.json();
        setSelectedUser(json);
    };

    return (
        <div className='usertable'>
                {selectedUser && (
                <div className="card well-lg p-2 mb-1">
                    <div className="panel panel-primary">
                        <div className="panel-heading">
                            <h3>{selectedUser.name} Details</h3>
                        </div>
                        <div className="panel-body">
                            <div className="row">
                            <div className="col-sm-4">
                                <div><strong>Username: </strong>{selectedUser.username}</div>
                                <div><strong>Email: </strong>{selectedUser.email}</div>
                                <div><strong>Website: </strong>{selectedUser.website}</div>
                                <div><strong>Phone: </strong>{selectedUser.phone}</div>
                            </div>
                            <div className="col-sm-8">
                                <div>
                                <strong>Address: </strong> {selectedUser.address.suite}, 
                                    {selectedUser.address.street},
                                    {selectedUser.address.city},
                                    {selectedUser.address.zipcode}
                                </div>
                                <div><strong>Company: </strong>{selectedUser.company.name}</div>
                                <div><strong>Catch Phrase: </strong>{selectedUser.company.catchPhrase}</div>
                                <div><strong>Business: </strong>{selectedUser.company.bs}</div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
                )}
            <div className="card p-2">
                <h3>Users Table</h3>
                <table className="table table-striped table-hover p-">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        {props.data.users.map(user => (
                            <tr key={user.id}>
                                <td><Link onClick={() => handleUserClick(user)}>{user.id}</Link></td>
                                <td><Link onClick={() => handleUserClick(user)}>{user.username}</Link></td>
                                <td><Link onClick={() => handleUserClick(user)}>{user.name}</Link></td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        </div>
    );
}

export default UserTable;

