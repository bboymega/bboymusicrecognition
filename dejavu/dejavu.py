import argparse
import json
import os.path
import sys
import random
import string
from datetime import datetime
import mysql.connector
from argparse import RawTextHelpFormatter
from os.path import isdir
from urllib.parse import quote
from dejavu import Dejavu
from dejavu.logic.recognizer.file_recognizer import FileRecognizer
from dejavu.logic.recognizer.microphone_recognizer import MicrophoneRecognizer

DEFAULT_CONFIG_FILE = "dejavu.cnf.SAMPLE"

try:
    with open('../config/query_db_config.json') as conf:
        db = json.load(conf)
except IOError:
    with open('../../config/query_db_config.json') as conf:
        db = json.load(conf)

mydb = mysql.connector.connect(
  host=db["host"],
  user=db["user"],
  passwd=db["password"],
  database=db["database"]
)

mycursor = mydb.cursor()
mycursor.execute("CREATE TABLE IF NOT EXISTS query (id VARCHAR(64), filename VARCHAR(1024) , date_start VARCHAR(128), date_complete VARCHAR(128), run_time VARCHAR(128), result_a VARCHAR(4096), input_confidence_a VARCHAR(10), fingerprinted_confidence_a VARCHAR(10), result_b VARCHAR(4096), input_confidence_b VARCHAR(10), fingerprinted_confidence_b VARCHAR(10), result_c VARCHAR(4096), input_confidence_c VARCHAR(10), fingerprinted_confidence_c VARCHAR(10))")

def init(configpath):
    """
    Load config from a JSON file
    """
    try:
        with open(configpath) as f:
            config = json.load(f)
    except IOError as err:
        print(f"Cannot open configuration: {str(err)}. Exiting")
        sys.exit(1)

    # create a Dejavu instance
    return Dejavu(config)


if __name__ == '__main__':
    parser = argparse.ArgumentParser(
        description="Dejavu: Audio Fingerprinting library",
        formatter_class=RawTextHelpFormatter)
    parser.add_argument('-c', '--config', nargs='?',
                        help='Path to configuration file\n'
                             'Usages: \n'
                             '--config /path/to/config-file\n')
    parser.add_argument('-f', '--fingerprint', nargs='*',
                        help='Fingerprint files in a directory\n'
                             'Usages: \n'
                             '--fingerprint /path/to/directory extension\n'
                             '--fingerprint /path/to/directory')
    parser.add_argument('-r', '--recognize', nargs=2,
                        help='Recognize what is '
                             'playing through the microphone or in a file.\n'
                             'Usage: \n'
                             '--recognize mic number_of_seconds \n'
                             '--recognize file path/to/file \n')
    args = parser.parse_args()

    if not args.fingerprint and not args.recognize:
        parser.print_help()
        sys.exit(0)

    config_file = args.config
    if config_file is None:
        config_file = DEFAULT_CONFIG_FILE

    djv = init(config_file)
    if args.fingerprint:
        # Fingerprint all files in a directory
        if len(args.fingerprint) == 2:
            directory = args.fingerprint[0]
            extension = args.fingerprint[1]
            print(f"Fingerprinting all .{extension} files in the {directory} directory")
            djv.fingerprint_directory(directory, ["." + extension], 4)

        elif len(args.fingerprint) == 1:
            filepath = args.fingerprint[0]
            if isdir(filepath):
                print("Please specify an extension if you'd like to fingerprint a directory!")
                sys.exit(1)
            djv.fingerprint_file(filepath)

    elif args.recognize:
        # Recognize audio source
        songs = None
        source = args.recognize[0]
        opt_arg = args.recognize[1]
        start_date = datetime.now()
        if source in ('mic', 'microphone'):
            songs = djv.recognize(MicrophoneRecognizer, seconds=opt_arg)
        elif source == 'file':
            songs = djv.recognize(FileRecognizer, opt_arg)
            
        if(len(songs) != 0):
            id = ''.join(random.choice(string.digits) for _ in range(13))
            my_path = os.path.abspath(os.path.dirname(__file__))
            path = os.path.join(my_path, "../public/details/")
            details = open(path+id,"w")
            details.write(str(songs[0])+"\n"+str(songs[1])+"\n"+str(songs[2])+"\n")
            details.close()
            mycursor.execute("SELECT * FROM query WHERE id LIKE "+id)
            while(mycursor.fetchone() != None):
                id = ''.join(random.choice(string.digits) for _ in range(13))
            complete_date = datetime.now()
            sql = "INSERT INTO query (id, filename, date_start, date_complete, run_time, result_a, input_confidence_a, fingerprinted_confidence_a, result_b, input_confidence_b, fingerprinted_confidence_b, result_c, input_confidence_c, fingerprinted_confidence_c) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)"
                
            val = (id, opt_arg ,start_date, complete_date, complete_date-start_date, songs[0]["song_name"], songs[0]["input_confidence"], songs[0]["fingerprinted_confidence"], songs[1]["song_name"], songs[1]["input_confidence"], songs[1]["fingerprinted_confidence"], songs[2]["song_name"], songs[2]["input_confidence"], songs[2]["fingerprinted_confidence"])
            mycursor.execute(sql, val)
            mydb.commit()
            print('<meta http-equiv="refresh" content="0; url=./result/'+id+'">')
        else:
            print('<meta http-equiv="refresh" content="0; url=./error/identifyerror">')
