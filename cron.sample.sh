#!/bin/bash

# CONFIGURATION
API_URL="http://localhost:5050/couchpotato/api/API_KEY/"
DIR_PATH="/var/www/tmp/"


#########################################################
#				DO NOT EDIT BELOW
#########################################################

# Appel de l'API pour avoir le nombre de films
NUM_JSON=$(wget "${API_URL}movie.list/?limit_offset=1&release_status=done" -q -O -)

# Parse json pour avoir le nombre de films
NUM_MOVIES=$(echo ${NUM_JSON} | sed -e 's/^.*"total": \([0-9]*\),.*$/\1/')

# Si NUM_MOVIES > 1000 => téléchargements de la liste par lot de 500
if [ ${NUM_MOVIES} -gt 1000 ]; then
        CUT=$(( ${NUM_MOVIES}/500 + 1 ))
        COUNT=0
        while [ ${COUNT} -lt ${CUT} ]; do
                wget "${API_URL}movie.list/?limit_offset=500%2C$(( ${COUNT} * 500 ))&release_status=done" -O "/tmp/couchpotato_api.${COUNT}"
                COUNT=$(( ${COUNT} + 1  ))
        done
else
        wget "${API_URL}movie.list/?&release_status=done" -O "/tmp/couchpotato_api.0"
fi

# Suppression du contenu du dossier contenant les résultats de l'api
rm -f ${DIR_PATH}*

# Déplacement des fichiers fraichement téléchargés
mv /tmp/couchpotato_api.* ${DIR_PATH}