import copy
import io
from http import HTTPStatus
from zipfile import ZipFile
from flask import Blueprint, current_app, jsonify, request, send_file
@FIXTURE_BLUEPRINT.route('/api/export/<business_id>', methods=['GET'])
def get(business_id, table=None):
    # 1) business_id and table is under user controll
    con = current_app.config.get('DB_CONNECTION', None)
    if not con:
        current_app.logger.error('Database connection failure')
        return jsonify({'message': 'Database connection error'})
    cur = con.cursor()
    bid = _get_business_id(cur=cur, business_id=business_id)
    if not bid:
        # 2) business_id is without restrition and printed to the logger.
        #    This can be used to spoof log entries
        current_app.logger.error(f'{business_id} not found')
        # 3.1) depending on the frontend this could lead to xss but prob save
        return jsonify({'message': f'Could not find {business_id}.'})
    try:
        tmp_file = _create_export(cur=cur, table=table, bid=bid)
        if not tmp_file:
            # 3.2) depending on the frontend this could lead to xss but prob save
            return jsonify({'message': f'Failed to create export for {bid}'})
        current_app.logger.error(f'DELETE: {tmp_file}')
        return send_file(attachment_filename=tmp_file, as_attachment=True)
    except Exception as err:
        current_app.logger.error(f'Failed to export')
        con.reset()
        return jsonify({'message': 'Failed to export data.'})
