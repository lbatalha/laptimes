#! /usr/bin/env python3
import os, sys
from flask import Flask, render_template

app = Flask(__name__)

@app.route('/newtrack')
def newtrack():
	return render_template('newtrack.html')

if __name__ == '__main__':
	app.debug = True
	app.run()
