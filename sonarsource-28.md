---

title: sonarsource-28
author: raxjs
tags: [csharp]

---

The nuget install Form. Assume that txtPackage.Text is under user control.

<!--more-->
{{< reference src="https://twitter.com/SonarSource/status/1342122866440101889" >}}

# Code
{{< code language="csharp"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;
using System.IO;
using System.Diagnostics;
using System.Reflection;
namespace core_api
{
    public partial class Form1 : Form
    {
        const int MAX_PATH = 10;
        public Form1()
        {
            InitializeComponent();
        }
        private void btnInstallPackage_Click(object sender, EventArgs e) {
            InstallPackage(txtPackage.Text, CurrentProject.ProjectDirectory);
        }
        public static void InstallPackage(string packageId, string workingDir) {
            string dir = Path.Combine(workingDir, "nuget");
            dir = Path.Combine(dir, packageId).Substring(0, MAX_PATH);
            Directory.CreateDirectory(dir);
            Process nuget = new Process();
            nuget.StartInfo.FileName = Path.Combine(Tools.GetPath(), "nuget");
            nuget.StartInfo.Arguments = "install "+packageId+" -NonInteractive";
            nuget.StartInfo.CreateNoWindow = true;
            nuget.StartInfo.RedirectStandardOutput = true;
            nuget.StartInfo.RedirectStandardError = true;
            nuget.StartInfo.WorkingDirectory = dir;
            nuget.StartInfo.StandardOutputEncoding = System.Text.Encoding.UTF8;
            nuget.StartInfo.UseShellExecute = false;
            nuget.Start();
        }
    }
    class Tools
    {
        public static string GetPath()
        {
            return "D:\\test\\VisualNuget\\nuget";
        }
    }
    class CurrentProject
    {
        public static string ProjectDirectory = "D:\\test\\VisualNuget";
    }
}


{{< /code >}}

# Solution
{{< code language="csharp" highlight="24,28,30,34,36-38" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;
using System.IO;
using System.Diagnostics;
using System.Reflection;
namespace core_api
{
    public partial class Form1 : Form
    {
        const int MAX_PATH = 10;
        public Form1()
        {
            InitializeComponent();
        }
        private void btnInstallPackage_Click(object sender, EventArgs e) {
	    // 1) assume txtPackage.Text is user input from a text import field
            InstallPackage(txtPackage.Text, CurrentProject.ProjectDirectory);
        }
        public static void InstallPackage(string packageId, string workingDir) {
	    // 2) then packageId is user input
            string dir = Path.Combine(workingDir, "nuget");
	    // 3) dir = D:\\test\\VisualNuget\\$packageId
            dir = Path.Combine(dir, packageId).Substring(0, MAX_PATH);
            Directory.CreateDirectory(dir);
            Process nuget = new Process();
	    // 4) nuget.StartInfo.FileName = D:\\test\\VisualNuget\\nuget\\nuget
            nuget.StartInfo.FileName = Path.Combine(Tools.GetPath(), "nuget");
	    // 5) argument injection via $packageId. nuget install:
	    //    https://docs.microsoft.com/en-us/nuget/reference/cli-reference/cli-ref-install
	    //    For example -Source could be interesting to install form an alternative server.
            nuget.StartInfo.Arguments = "install "+packageId+" -NonInteractive";
            nuget.StartInfo.CreateNoWindow = true;
            nuget.StartInfo.RedirectStandardOutput = true;
            nuget.StartInfo.RedirectStandardError = true;
            nuget.StartInfo.WorkingDirectory = dir;
            nuget.StartInfo.StandardOutputEncoding = System.Text.Encoding.UTF8;
            nuget.StartInfo.UseShellExecute = false;
            nuget.Start();
        }
    }
    class Tools
    {
        public static string GetPath()
        {
            return "D:\\test\\VisualNuget\\nuget";
        }
    }
    class CurrentProject
    {
        public static string ProjectDirectory = "D:\\test\\VisualNuget";
    }
}
{{< /code >}}
