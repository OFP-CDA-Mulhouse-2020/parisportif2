#!/usr/bin/env bash
cp githook/pre-commit.sh .git/hooks/pre-commit
chmod a+rwx,g-w,o-w .git/hooks/pre-commit