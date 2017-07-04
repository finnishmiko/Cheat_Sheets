# GIT

## Associate username and email to Git:
```sh
git config --global user.name "My Name"
git config --global user.email "myemail@address.com"
```

Check name and email:
```sh
git config user.name
git config user.email
```

## New Github repository - option 1
* Create new repository to Github with README.md and .gitignore files
* Clone that repository to your computer
```
git clone <path to github>
```
* Edit README.md and .gitignore as needed
* Copy your files to that folder and add them to Git
```sh
git add -A
git commit -m "initial commit"
git push origin master
```

## New Github repository - option 2
* Create new repository to Github without README.md and .gitignore files
* In you computer go to project folder and initialize new Git repository
```
git init
```
* Add your program files to Git
```sh
git add .
git commit -m "First commit"
```
* From Github copy your repository URL and add it as a remote
```sh
git remote add origin <repository URL>
```
* And push your changes to Github:
```sh
git push origin master
```


## Branch
```sh
git branch # Check which branch you are working
git branch new_branch
git checkout branch_name # Switch to the new branch

# Then git add and commit as usual
git push origin your_branch_name

git merge branch_name # First switch to master branch so new branch changes will be made to master. This works when there is no changes in master.

git branch -d branch_name # will delete branch

```

## Local modifications prevent `git pull origin master`
* Git is protecting you from losing the local modifications
```sh
error: Your local changes to the following files would be overwritten by merge:
        <filename>
Please commit your changes or stash them before you merge.
Aborting
```

[Options](https://stackoverflow.com/questions/15745045/how-do-i-resolve-git-saying-commit-your-changes-or-stash-them-before-you-can-me):

1. Commit the change using
`git commit -m "My message"`

2. Stash it to a stack with `git stash`. Then do the merge and  pop your changes back in reverse order:
`git stash pop`

3. Discard all local changes using `git reset --hard` or discard local changes for a specific file using `git checkout filename`


## Save Git log history to a text file
```sh
git --no-pager log > log.txt
```

## Set Git-server in uSD-card in local computer:
* Go to uSD-drive (f.ex. D:/) and create a repository folder and in it new project repository:
```sh
mkdir newrepo.git
git init --bare # use .git ending and --bare
```
* If this repository is needed to be cloned over IP then rename /hooks/post-update.sample to post-update and run
```sh
git update-server-info [--force] # Without this cloning does not work to another computer over IP
```
* Go to C:drive project folder and
```sh
git init
git remote add origin d:/repofolder/newrepo.git # origin can be called something else also
```
* Then as usually add, commit and push new files


## Create remote repository to Virtual Machine

* Create new username:
```sh
sudo adduser username
```
* Create new group called git-users and change its permissions:
```sh
sudo addgroup git-users
sudo usermod -a -G git-users username
groups username
sudo chown -R root:git-users /srv/git/
```

[Repair permissions](https://stackoverflow.com/questions/6448242/git-push-error-insufficient-permission-for-adding-an-object-to-repository-datab#6448326)
```sh
git config core.sharedRepository
# If it's not group or true or 1 or some mask, try running:

git config core.sharedRepository group

# and then run
cd /path/to/repo.git
chgrp -R git-users .
chmod -R g+rwX .
find . -type d -exec chmod g+s '{}' +

# or all in once with sudo
sudo chgrp -R git-users . && sudo chmod -R g+rwX . && sudo find . -type d -exec chmod g+s '{}' +
```



```sh
git remote add origin <username>@<path to server>:/srv/git/test.git

git clone <username>@<path to server>:/srv/git/test.git
```
