#!/usr/bin/env bash
set -e

# Internal Field Separator
# Separate multiple directories with a commma
IFS=','

read -ra local_dirs <<<"$1"
REMOTE_DIR=$2

for local_dir in "${local_dirs[@]}"; do
  if [ -d "$local_dir" ]; then
    lftp -c "
      open $HOST
      user $USERNAME $PASSWORD
      mirror -R "$local_dir" "$REMOTE_DIR/$local_dir"
      bye
    "
  else
    echo "Error: Local directory $local_dir does not exist."
    exit 1
  fi
done

unset IFS
