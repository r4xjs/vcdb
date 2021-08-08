---

title: joernchen-02
author: raxjs
tags: [go]

---

$DESCRIPTION

<!--more-->
{{< reference src="https://code-audit-training.gitlab.io/" >}}

# Code
{{< code language="go"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
package main

import (
    "archive/zip"
    "fmt"
    "io"
    "log"
    "os"
    "path/filepath"
    "strings"
)

func main() {

    files, err := Unzip("done.zip", "output")

    if err != nil {
        log.Fatal(err)
    }

    fmt.Println("Unzipped: " + strings.Join(files, ", "))

}

// Unzip will un-compress a zip archive,
// moving all files and folders to an output directory
func Unzip(src, dest string) ([]string, error) {

    var filenames []string

    r, err := zip.OpenReader(src)
    if err != nil {
        return filenames, err
    }
    defer r.Close()

    for _, f := range r.File {

        rc, err := f.Open()
        if err != nil {
            return filenames, err
        }
        defer rc.Close()

        // Store filename/path for returning and using later on
        fpath := filepath.Join(dest, f.Name)
        filenames = append(filenames, fpath)

        if f.FileInfo().IsDir() {

            // Make Folder
            os.MkdirAll(fpath, os.ModePerm)

        } else {

            // Make File
            var fdir string
            if lastIndex := strings.LastIndex(fpath, string(os.PathSeparator)); lastIndex > -1 {
                fdir = fpath[:lastIndex]
            }

            err = os.MkdirAll(fdir, os.ModePerm)
            if err != nil {
                log.Fatal(err)
                return filenames, err
            }
            f, err := os.OpenFile(
                fpath, os.O_WRONLY|os.O_CREATE|os.O_TRUNC, f.Mode())
            if err != nil {
                return filenames, err
            }
            defer f.Close()

            _, err = io.Copy(f, rc)
            if err != nil {
                return filenames, err
            }

        }
    }
    return filenames, nil
}
{{< /code >}}

# Solution
{{< code language="go" highlight="" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
package main

import (
    "archive/zip"
    "fmt"
    "io"
    "log"
    "os"
    "path/filepath"
    "strings"
)

func main() {
    // 1) files is user input
    files, err := Unzip("done.zip", "output")

    if err != nil {
        log.Fatal(err)
    }

    fmt.Println("Unzipped: " + strings.Join(files, ", "))

}

// Unzip will un-compress a zip archive,
// moving all files and folders to an output directory
func Unzip(src, dest string) ([]string, error) {

    var filenames []string
    // 2) src and r is user input
    r, err := zip.OpenReader(src)
    if err != nil {
        return filenames, err
    }
    defer r.Close()

    for _, f := range r.File {

        rc, err := f.Open()
        if err != nil {
            return filenames, err
        }
        defer rc.Close()

        // 3) f.Name is user input, so fpath is also under user control
        // Store filename/path for returning and using later on
        fpath := filepath.Join(dest, f.Name)
        filenames = append(filenames, fpath)

        if f.FileInfo().IsDir() {

            // Make Folder
            os.MkdirAll(fpath, os.ModePerm)

        } else {

            // Make File
            var fdir string
            if lastIndex := strings.LastIndex(fpath, string(os.PathSeparator)); lastIndex > -1 {
                fdir = fpath[:lastIndex]
            }

            err = os.MkdirAll(fdir, os.ModePerm)
            if err != nil {
                log.Fatal(err)
                return filenames, err
            }
            // 4) fpath is under user control and used in os.OpenFile here...
            //    directory traversal
            f, err := os.OpenFile(
                fpath, os.O_WRONLY|os.O_CREATE|os.O_TRUNC, f.Mode())
            if err != nil {
                return filenames, err
            }
            defer f.Close()

            _, err = io.Copy(f, rc)
            if err != nil {
                return filenames, err
            }

        }
    }
    return filenames, nil
}

{{< /code >}}