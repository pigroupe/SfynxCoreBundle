#!/bin/sh

set -x

map_uid () {
  old_uid=$(id -u "$1")
  command -v usermod 2>/dev/null 1>&2
  if [ "$?" -ne 0 ]; then
    sed -ri "s/^($1:[^:]+:)([^:]+)(:.+)/\1$2\3/" /etc/passwd
  else
    (usermod -u "$2" "$1") || true
  fi
  # Update files related with the user
  find / -user $old_uid -exec chown "$2" {} 2>/dev/null + || true
}
map_gid () {
  old_gid=$(id -g "$1")
  command -v groupmod 2>/dev/null 1>&2
  if [ "$?" -ne 0 ]; then
    sed -ri "s/^($1:[^:]+:)[[:digit:]]+/\1$2/" /etc/group
  else
    (groupmod -g "$2" "$1") || true
  fi
  # Update users primary group if its the changed one
  sed -ri "s/^([^:]+:[^:]+:[[:digit:]]+):$old_gid:/\1:$2:\3/" /etc/passwd
  # Update files related with the group
  find / -group $old_gid -exec chgrp "$2" {} 2>/dev/null + || true
}
add_wwwdata_to_33() {
  command -v usermod 2>/dev/null 1>&2
  if [ "$?" -eq 0 ]; then
    (usermod -a -G 33 www-data) || true
  fi
}
